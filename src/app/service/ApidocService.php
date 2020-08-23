<?php

namespace app\service;

/**
 * Class ApidocService
 * @package app\service
 *
 * Howto
 *
 * $content = ApidocService::generateApidocForClassFile($class, $protocol . '://' . $host);
 *
 *
 */
class ApidocService
{
    /**
     * @param array $actions   - ['$action_url' => 'method_name']
     * @param object $obj      - object to generate docs from
     * @param string $base_url -  HOST/$base_url/$action_url?parameters
     * @return array
     * @throws \ReflectionException
     */
    public static function generateApidocForActionList($actions, $obj, $base_url = '') {
        $content = [];
        $r = new \ReflectionClass(get_class($obj));
        foreach ($actions as $id => $action) {
            $doc = $r->getMethod($action)->getDocComment();
            self::parseApidocComment($doc, $content, $base_url, $id);
        }

        return $content;
    }

    /**
     * @param object|string $class      - object/class name to generate docs from
     * @param string $base_url -  HOST/$base_url/$action_url?parameters
     * @return array
     * @throws \ReflectionException
     */
    public static function generateApidocForClass($class, $base_url = '') {

        $content = [];
        if (is_object($class)) {
            $class = get_class($class);
        }

        $r = new \ReflectionClass();

        $doc = $r->export($class, true);

        self::parseApidocComment($doc, $content, $base_url);

        return $content;
    }

    /**
     * @param object|string $class      - object/class name to generate docs from
     * @param string $base_url -  HOST/$base_url/$action_url?parameters
     * @return array
     * @throws \ReflectionException
     */
    public static function generateApidocForClassFile($class, $base_url = '') {

        $content = [];
        $r = new \ReflectionClass($class);

        $f = $r->getFileName();

        $source = file($f);
        $source = implode('', array_slice($source, 0, count($source)));

        self::parseApidocComment($source, $content, $base_url);

        return $content;
    }

    /**
     * Reads Apidocs from the docblocs
     *
     * 1. Format @Api(key=value,..)
     * 2. key - some string
     * 3. value must be ether a quoted string (foo="bar") or a JSON in {} (foo={"a":1,"b":2})
     *
     * Example:
     * @Api(name="user_by_id", method="get", action_url="api/user", url_params="?id={userid}", parameters={"userid":{"type":"string"}})
     * or
     * @Api(name="getapidoc", method="get", action_url="api/getapidoc")
     *
     * @return array
     * @throws \ReflectionException
     */
    protected static function parseApidocComment($doc, &$result, $base_url = '', $id = null) {
        if (preg_match_all('/@Api\((.*?)\)\n/s', $doc, $annotations)) {
            foreach ($annotations[1] as $idx => $annotation) {
                $annotationnew = [];
                $annotation = preg_split('/(?!\\"),(?!\s*")/', $annotation);
                foreach ($annotation as $pair) {
                    $pair = trim($pair);
                    $key = '';
                    $value = '';
                    if (preg_match_all('/([^=]+)=({.*})$/', $pair, $pairdata)) {
                        $key = $pairdata[1][0];
                        $value = json_decode($pairdata[2][0], true);
                    } elseif (preg_match_all('/([^=]+)="(.+)"$/', $pair, $pairdata)) {
                        $key = $pairdata[1][0];
                        $value = $pairdata[2][0];
                    }

                    if ($key !== '' && $value !== '') {
                        $annotationnew[$key] = $value;
                    }
                }

                $url_params = (isset($annotationnew['url_params'])) ? $annotationnew['url_params'] : '' ;

                $url = ($id !== null) ?
                    $base_url . '/' . $id :
                    $base_url . '/' . $annotationnew['action_url'];

                $annotationnew['url'] = $url . $url_params;

                $block_id = $id;
                if (empty($block_id)) {
                    $block_id = !empty($annotationnew['action_url']) ? $annotationnew['action_url'] : 'assorted';
                }

                unset($annotationnew['action_url']);
                unset($annotationnew['url_params']);

                $result[$block_id][$idx] = $annotationnew;
            }
        }
    }
}

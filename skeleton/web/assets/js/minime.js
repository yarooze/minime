/**
 * Module with helper functions for objects and arrays.
 * 
 */
;(function (global) {

    var MINIME = {};
    
    /**
     * Returns the value (if exists) of the object or array by path
     * Example:
     * var obj = {foo: [bar, {a: 1, b: 2}]}
     * getByPath("foo[1].a", obj) returns 1
     *
     * @param {string|Array} path e.g "a.b.c" or "a[b].c" or ["a","b","c"]
     * @param {object} obj e.g obj[a][b][c]
     * @param {*} def default value to return
     *
     * @return {*}
     */
    MINIME.getByPath = function getByPath(path, obj, def) {
        var res = obj,
            index, key;
        if (typeof path === "string") {
            path = path.replace("[", ".");
            path = path.replace("]", "");
            path = path.split(".");
        }
        for (index = 0; index < path.length; index++) {
            key = path[index];
            if (res && key in res) {
                res = res[key];
                continue;
            }
            return def;
        }

        return res;
    };

    /**
     * Sets the value of the object's property or array by path. Also creates property if not exists.
     * "a.b[7].d" = "foo" --> {a: {b: [6 x undefined, {d: "foo"}]}}
     * @param {string|Array} path e.g "a.b[7].c"|["a","b[","7","c"]
     * @param {object} obj e.g obj.a.b.c
     * @param {*} value
     */
    MINIME.setByPath = function setByPath(path, obj, value) {
        if (typeof path === "string") {
            // replace array brackets but keep one to differentiate between empty {} and []
            path = path.replace("[", "[.");
            path = path.replace("]", "");
            path = path.split(".");
        }
        if (path.length > 1) {
            var e = path.shift(),
                defaultEmpty = {};
            if (e.indexOf("[") !== -1) {
                e = e.replace("[", "");
                defaultEmpty = [];
            }
            if (((typeof obj[e] !== "object") || (obj[e] === null))) {
                obj[e] = defaultEmpty;
            }
            setByPath(path, obj[e], value);
        } else {
            obj[path[0]] = value;
        }
    };

  global.MINIME = MINIME;
  
})(window);

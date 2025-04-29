const Ziggy = { "url": "http:\/\/localhost", "port": null, "defaults": {}, "routes": { "Home": { "uri": "\/", "methods": ["GET", "HEAD"] }, "Test": { "uri": "test", "methods": ["GET", "HEAD"] }, "storage.local": { "uri": "storage\/{path}", "methods": ["GET", "HEAD"], "wheres": { "path": ".*" }, "parameters": ["path"] } } };
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };

if (!self.define) {
    const e = e=>{
        "require" !== e && (e += ".js");
        let i = Promise.resolve();
        return r[e] || (i = new Promise(async i=>{
            if ("document"in self) {
                const r = document.createElement("script");
                r.src = e,
                document.head.appendChild(r),
                r.onload = i
            } else
                importScripts(e),
                i()
        }
        )),
        i.then(()=>{
            if (!r[e])
                throw new Error(`Module ${e} didn’t register its module`);
            return r[e]
        }
        )
    }
      , i = (i,r)=>{
        Promise.all(i.map(e)).then(e=>r(1 === e.length ? e[0] : e))
    }
      , r = {
        require: Promise.resolve(i)
    };
    self.define = (i,s,c)=>{
        r[i] || (r[i] = Promise.resolve().then(()=>{
            let r = {};
            const n = {
                uri: location.origin + i.slice(1)
            };
            return Promise.all(s.map(i=>{
                switch (i) {
                case "exports":
                    return r;
                case "module":
                    return n;
                default:
                    return e(i)
                }
            }
            )).then(e=>{
                const i = c(...e);
                return r.default || (r.default = i),
                r
            }
            )
        }
        ))
    }
}
define("./serviceworker.js", ["./workbox-b3c3201a"], (function(e) {
    "use strict";
    self.addEventListener("message", e=>{
        e.data && "SKIP_WAITING" === e.data.type && self.skipWaiting()
    }
    ),
    e.precacheAndRoute([{
        url: "css/app.css",
        revision: "cb3dc398c7c0558d2a0357a867e736c3"
    }, {
        url: "favicon.ico",
        revision: "d41d8cd98f00b204e9800998ecf8427e"
    }, {
        url: "images/dashboard.jpg",
        revision: "d7ed699e89d441beb0df6436f3673c19"
    }, {
        url: "images/default.png",
        revision: "cd6d11ef068a3f0d483a61b73044e4ea"
    }, {
        url: "images/icons/icon-128x128.png",
        revision: "9b90c2ceac650d9dcaefd1073e91c4aa"
    }, {
        url: "images/icons/icon-144x144.png",
        revision: "7e44bb2fd2edeef12ecd2db1a4fdb519"
    }, {
        url: "images/icons/icon-152x152.png",
        revision: "2c79de55cc6590466868f15d5b264715"
    }, {
        url: "images/icons/icon-192x192.png",
        revision: "2c35c132271d2e280674e844b210995f"
    }, {
        url: "images/icons/icon-384x384.png",
        revision: "7736742388a51af7cf3b79a01ac38da5"
    }, {
        url: "images/icons/icon-512x512.png",
        revision: "2af6eac6af7f9c3197fc83457a1e631d"
    }, {
        url: "images/icons/icon-72x72.png",
        revision: "2d26ec3d772972fcaed9387ba7516e90"
    }, {
        url: "images/icons/icon-96x96.png",
        revision: "d2049b8c586dafea3b32a1bf2cfe7213"
    }, {
        url: "images/logo-footer.png",
        revision: "e5a84002d19cc49d28ac061bf88af878"
    }, {
        url: "images/logo-mobile.png",
        revision: "a4771d968f43177851971a6f05281f4a"
    }, {
        url: "images/logo.png",
        revision: "2086bfcd7c80f13e88a75784de85d2e7"
    }, {
        url: "mix-manifest.json",
        revision: "207fd484b7c2ceeff7800b8c8a11b3b6"
    }, {
        url: "OneSignalSDKUpdaterWorker.js",
        revision: "2918704e7d1da185af1db83658e40e8b"
    }, {
        url: "OneSignalSDKWorker.js",
        revision: "2918704e7d1da185af1db83658e40e8b"
    }, {
        url: "uploads/images/_default.jpg",
        revision: "c5c15083852aee2cd2085a1419c0e690"
    }], {})
}
));


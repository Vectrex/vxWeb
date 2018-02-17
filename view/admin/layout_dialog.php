<!DOCTYPE html>

<html>
	<head>

		<title> vxPHP File Browser</title>
		<meta name='keywords' content=''>
		<meta name='description' content=''>

		<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
		<meta name='author' content='Gregor Kofler - Mediendesign und Webapplikationen, http://gregorkofler.com'>

		<link type='text/css' rel='stylesheet' href='/css/admin.css'>
        <!-- <script type="text/javascript" src="/js/admin/vxjs.js"></script> -->
        <script type="text/javascript" src="/js/vendor/vxJS/core.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/xhr.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widget.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/xhrform.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/sortable.js"></script>

        <script type="text/javascript">
            if(!this.vxWeb) {
                this.vxWeb = {};
            }
            if(!this.vxWeb.routes) {
                this.vxWeb.routes = {};
            }

            vxWeb.messageToast = function(selector) {

                var mBox, lastAddedClass, timeoutId, button;

                var hide = function() {
                    if(mBox) {
                        mBox.classList.remove("display");
                    }
                };

                var show = function(msg, className) {

                    if(mBox === undefined) {
                        mBox = document.querySelector(selector || "#messageBox");

                        if(mBox && (button = mBox.querySelector("button"))) {
                            button.addEventListener("click", hide);
                        }
                    }

                    if(mBox) {
                        if(lastAddedClass) {
                            mBox.classList.remove(lastAddedClass);
                        }
                        if(className) {
                            mBox.classList.add(className);
                        }
                        lastAddedClass = className;
                    }

                    mBox.innerHTML = msg;
                    mBox.appendChild(button);
                    mBox.classList.add("display");

                    if(timeoutId) {
                        window.clearTimeout(timeoutId);
                    }
                    timeoutId = window.setTimeout(hide, 5000);

                };

                return {
                    show: show,
                    hide: hide
                };

            };
        </script>

	</head>

	<body>
		<div id="page" class="embedded" style="padding: 1em;">
			<!-- { block: content_block } -->
		</div>
	</body>
</html>

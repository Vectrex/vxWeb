<!doctype html>

<html>
	<head>
        <?= $this->includeFile('admin/snippets/html_head.php') ?>

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
		<div id="page" class="single-column">
			<!-- { block: content_block } -->
            <div id="messageBox" class="toast"><button class="btn btn-clear float-right"></button></div>
		</div>

    </body>
</html>

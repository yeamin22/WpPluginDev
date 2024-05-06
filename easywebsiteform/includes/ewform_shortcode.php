<?php
/*
 * Easy Website Form Shortcode Functions
 */
if ( !defined( 'ABSPATH' ) ) {die( "Don't try this" );};
if ( !class_exists( "EWform_Shortcode" ) ) {
    class EWform_Shortcode {
        function __construct() {
            add_action( "wp_head", [$this, "ewform_style_push"] );
            if ( !shortcode_exists( "ewform" ) ) {
                add_shortcode( "ewform", [$this, "ewform_callback_fun"] );
            }
        }
        /**
         * @param $attr
         * @param $content
         * @return false|string
         */
        function ewform_callback_fun( $attr, $content ) {
            $api_key = get_option( 'ewform_key' ) ? get_option( 'ewform_key' ) : '';

            // content to array
            $content = explode( "/", $content );
            extract( shortcode_atts( [
                'title' => esc_html__( "Easy Website Form", "easywebsiteform" ),
                'id'    => end( $content ),
            ], $attr ) );

            if ( empty( $id ) ) {
                return sprintf( "<h4>%s</h4>", esc_html__( "Form Not Found/Broken Url Provided", "easywebsiteform" ) );
            }

            if ( empty( $api_key ) ) {
                return sprintf( "<p style='color:#ff3b58'>%s<b>%s</b></p>", esc_html__( "Please Connect Your Account With Api key of", "easywebsiteform" ), esc_html__( "Easy Website Form", "easywebsiteform" ) );
            }
            ob_start();
            ?>
            <div class="ew_form_wrapper">
                <div id="iframe-overlay-<?php echo esc_attr( $id ); ?>"></div>
                <div id="iframe-loader-<?php echo esc_attr( $id ); ?>"
                    style="background-image: url('<?php echo esc_url( EWFORM_URL ); ?>/assets/img/Iframe-loader.gif');">
                </div>
                <div id="iframe-<?php echo esc_attr( $id ); ?>"></div>
                <script type="text/javascript">
                    function EWFFormWizard(formId, formTitle) {
                        const iframeId = `easywebsiteform-${formId}`;
                        const iframeContainerId = `iframe-${formId}`;
                        const iframeLoaderId = `iframe-loader-${formId}`;
                        const iframeOverlayId = `iframe-overlay-${formId}`;

                        this.wizardIFrame = function () {
                            const iframe = document.createElement('iframe');
                            iframe.name = 'easywebsiteform';
                            iframe.setAttribute('data-formid', formId);
                            iframe.id = iframeId;
                            iframe.title = formTitle;
                            iframe.className = 'easywebsiteform_iframe';
                            iframe.src = `<?php echo esc_url( EWFORM_FRONTEND_URL ); ?>/form/${formId}`;
                            iframe.allowtransparency = 'true';
                            iframe.allowfullscreen = true;
                            iframe.style.width = '100%';
                            iframe.style.maxWidth = '100%';
                            iframe.style.border = 'none';
                            iframe.scrolling = 'no';
                            iframe.onload = function () {
                                window.parent.scrollTo(0, 0);
                            };

                            const iframeContainer = document.getElementById(iframeContainerId);
                            if (iframeContainer){
                                iframeContainer.appendChild(iframe);
                            }
                        }

                        this.wizardPlaceholder = function () {
                            const iframeElement = document.getElementById(iframeId);

                            if (iframeElement) {
                                iframeElement.addEventListener('load', function () {
                                    const iframeLoader = document.getElementById(iframeLoaderId);
                                    const iframeOverlay = document.getElementById(iframeOverlayId);

                                    if (iframeLoader) {
                                        iframeLoader.style.display = 'none';
                                    }

                                    if (iframeOverlay) {
                                        iframeOverlay.style.display = 'none';
                                    }
                                });
                            }
                        }
                        this.receiveIFrameMessage = function () {
                            function handleIFrameMessage(e) {
                                if (!e.data || !e.data.ewf) return;
                                const ewf = e.data.ewf;
                                const iframeElement = document.getElementById(`easywebsiteform-${ewf.uid || ""}`);
                                if (iframeElement) {
                                    iframeElement.style.height = (ewf.height || "") + "px";
                                }
                            }
                            window.addEventListener("message", handleIFrameMessage);
                        }
                    }
                    var ewfFormWizard = new EWFFormWizard(<?php echo esc_html( $id ); ?>, "<?php echo esc_html( $title ); ?>");
                    ewfFormWizard.wizardIFrame();
                    ewfFormWizard.wizardPlaceholder();
                    ewfFormWizard.receiveIFrameMessage();
                </script>
            </div>

            <?php
            return ob_get_clean();
        }

        /**
         * @return void
         */
        function ewform_style_push() {
            ?>
            <style id="ewform">
                .ew_form_wrapper {
                    position: relative;
                }
                .iframe_overlay {
                    background-color: rgba(0, 0, 0, 0.2);
                    background-size: cover;
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    width: 100%;
                    height: 100%;
                }
                .iframe_loader {
                    height: 100px;
                    display: block;
                    width: 100px;
                    position: absolute;
                    background-size: cover;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                }
            </style>
            <?php
}

    }
}
new EWform_Shortcode();
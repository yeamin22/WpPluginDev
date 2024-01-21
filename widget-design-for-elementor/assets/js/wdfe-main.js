jQuery( window ).on( 'elementor/frontend/init', () => {
  const addHandler = ( $element ) => {
    var atTypedJs =  $element[0].querySelector(".animated_text_wrap");
    var at_typed_wrapper =  $element[0].querySelector(".at_typed_text");
    var Strings=  atTypedJs.getAttribute('data-animated_text')
    var strings = Strings.split("|");
    strings.pop();   

    var TypedOptions = JSON.parse(atTypedJs.dataset.typedoptions);

    console.log(TypedOptions);
    var typed5 = new Typed(at_typed_wrapper, {
        strings: strings,
        typeSpeed: TypedOptions.typedSpeed,
        backDelay: TypedOptions.backDelay,
        cursorChar: TypedOptions.typedCursor,
        fadeOutClass: 'typed-fade-out',
        onBegin: (self) => {
          
        },
        shuffle: true,
        smartBackspace: false,
        loop: true
    });
  };

  elementorFrontend.hooks.addAction( 'frontend/element_ready/wdfe_animated_text.default', addHandler );
} );
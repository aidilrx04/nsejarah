/** CUSTOM SCRIPT */
$( function()
{

    let sContainer, sList, submitBtn, tambahBtn, kJenis, kMasaInput;
    sContainer = $( '#soalan' );
    sList= sContainer.find( '#soalan-list' );
    submitBtn = $( '#submit' );
    tambahBtn = $( '#tambah-soalan' );
    kJenis = $( '#jenis' );
    kMasaInput = $( '#masa' );

    tambahBtn.click( 'click', function () { tambahSoalan( sList ) } );
    kJenis.change( function( e ) 
    {

        const target = e.target;
        let valToDisable = 'latihan';

        if( target.value == valToDisable )
        {

            kMasaInput.attr( { disabled: true } );

        }
        else
        {

            kMasaInput.attr( { disabled: false } );

        }

    } );

} );

function tambahSoalan( sList )
{

    let sContainer, sInput, sImage, sjContainer, sPadamBtn;
    const sId = uniqid();

    //custom css
    var sInputContainer, sImage_BtnContainer, sjTitle, sjInputsContainer;

    sContainer = $( document.createElement( 'div' ) )
                 .attr( {
                     class: 'soalan'
                 } )
                 .appendTo( sList );

    sInputContainer = $( document.createElement( 'label' ) )
                      .attr( {
                          'class': 'input-container'
                      })
                      .appendTo( sContainer );
    sInput = $( document.createElement( 'input' ) )
             .attr( {
                class: 'input-field',
                placeholder: 'Sila masukkan teks soalan',
                name: 's[b][' + sId + '][]',
                required: true
             } )
             .appendTo( sInputContainer );
    sImage_BtnContainer = $( document.createElement( 'label' ) )
                          .attr( 
                              {
                                  'class': 'input-container'
                              }
                           )
                          .appendTo( sContainer );
    sImage = $( document.createElement( 'input' ) )
             .attr( {
                type: 'file',
                name: sId
             } )
             .appendTo( sImage_BtnContainer );

    sPadamBtn = $( document.createElement( 'button' ) )
                .attr( {
                    type: 'button',
                    'class': 'delete-new'
                } )
                .text( 'Padam Soalan' )
                .appendTo( sImage_BtnContainer ); 
    sPadamBtn.click( function () 
    {

        sContainer.remove();

    } );

    sjContainer = $( document.createElement( 'div' ) )
                  .attr( {
                      class: 'jawapan-container input-container'
                  } )
                  .appendTo( sContainer );
    
    sjTitle = $( document.createElement( 'h4' ) )
              .text( 'Jawapan' )
              .appendTo( sjContainer );

    const jawapanCount = 4;
    const jInput = [];

    for( let i = 0; i < jawapanCount; i++ )
    {

        const jId = uniqid();
        sjInputsContainer = $( document.createElement( 'div' ) ).appendTo( sjContainer )
        let jInput = $( document.createElement( 'input' ) )
                     .attr( {
                         type: 'text',
                         class: 'jawapan-input',
                         placeholder: 'Sila masukkan jawapan',
                         name: 's[b][' + sId + '][j][][0]',
                         required: true
                     } )
                     .appendTo( sjInputsContainer );

        let jBetul = $( document.createElement( 'input' ) )
                     .attr( {
                         type: 'radio',
                         value: i,
                         name: 's[b][' + sId + '][]',
                         required: true
                     } )
                     .appendTo( sjInputsContainer );
    }

    sContainer.append( document.createElement( 'hr' ) );

    return;

}

function uniqid() {
    var keys = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('')
    var id = false;

    do {

        id = '';

        for (var i = 0; i < 5; i++) {

            id += keys[Math.floor(Math.random() * keys.length)];

        }

        var _dummy = document.querySelector('#' + id);

        if (_dummy) {

            id = false;

        }

    } while (!id);

    return id;
}
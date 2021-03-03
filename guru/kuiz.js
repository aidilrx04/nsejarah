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

    sContainer = $( document.createElement( 'div' ) )
                 .attr( {
                     class: 'soalan'
                 } )
                 .appendTo( sList );

    sInput = $( document.createElement( 'input' ) )
             .attr( {
                class: 'input-field',
                placeholder: 'Sila masukkan teks soalan',
                name: 's[b][' + sId + '][]',
                required: true
             } )
             .appendTo( sContainer );
    
    sImage = $( document.createElement( 'input' ) )
             .attr( {
                class: 'input-field',
                type: 'file',
                name: sId
             } )
             .appendTo( sContainer );

    sPadamBtn = $( document.createElement( 'button' ) )
                .attr( {
                    type: 'button'
                } )
                .text( 'Padam Soalan' )
                .appendTo( sContainer ); 
    sPadamBtn.click( function () 
    {

        sContainer.remove();

    } );

    sjContainer = $( document.createElement( 'div' ) )
                  .attr( {
                      class: 'jawapan-container'
                  } )
                  .appendTo( sContainer );

    const jawapanCount = 4;
    const jInput = [];

    for( let i = 0; i < jawapanCount; i++ )
    {

        const jId = uniqid();
        let jInput = $( document.createElement( 'input' ) )
                     .attr( {
                         class: 'jawapan-input',
                         placeholder: 'Sila masukkan jawapan',
                         name: 's[b][' + sId + '][j][][0]',
                         required: true
                     } )
                     .appendTo( sjContainer );

        let jBetul = $( document.createElement( 'input' ) )
                     .attr( {
                         type: 'radio',
                         value: i,
                         name: 's[b][' + sId + '][]',
                         required: true
                     } )
                     .appendTo( sjContainer );
    }

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
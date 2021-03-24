window.onload = function()
    {

        var kurang, reset, tambah;

        kurang = document.querySelector( '#teks-kurang' );
        reset = document.querySelector( '#teks-reset' );
        tambah = document.querySelector( '#teks-tambah' );

        const butang = [kurang, reset, tambah];

        for( let i = 0; i < butang.length; i++ )
        {

            const btg = butang[i];

            btg.addEventListener( 'click', function(e)
            {
                e.preventDefault();
                let value = this.value;
                // console.log( value );
                const tables = document.querySelectorAll( 'table' );
                // console.log( tables );

                for( let j = 0; j < tables.length; j++ )
                {

                    const table = tables[j];
                    console.log( table );

                    if( value != 2 )
                    {

                        table.style.fontSize = ( parseFloat( table.style.fontSize || 1 ) + ( value * .2 ) ) + 'em';

                    }
                    else
                    {

                        table.style.fontSize = '1em';

                    }

                }

                return false;

            } );

        }

    };
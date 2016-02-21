<?php

echo get(new Dom('p'))->html(
        get(new Dom('strong'))->html(
                get(new Dom('span'))->att('class','bleuF')->html('Komu').
                get(new Dom('span'))->att('class', 'bleuC')->html('note')
        )
    ),
    get(new Dom('p'))->html('David Chabrier <br />E-motions SARL<br />SIREN 451901284'),
    get(new Dom('p'))->html('<?php echo("ok")?>');

?>

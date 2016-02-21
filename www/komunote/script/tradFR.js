formats={
    'numberLetterOnly':{
        reg:/^[A-Za-z0-9]{3,}$/,
        msg:"Le format est incorrect ! Lettres et chiffres uniquement"
    },
    'email':{
        reg:/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/,
        msg:"Le format est incorrect ! ex : mon_adresse-email@mon-fournisseur.fr"
    },
    'decimal':{
        reg:/^([0-9]{1,10})(\.[0-9]{1,2})?$/,
        msg:"Le format est incorrect ! ex : 15.45"
    },
    'integer':{
        reg:/^([0-9]{1,10})$/,
        msg:"Le format est incorrect ! ex : 122"
    }
};
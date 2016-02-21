formats={
    'numberLetterOnly':{
        reg:/^[A-Za-z0-9]{3,}$/,
        msg:"Incorrect format ! Letters and numbers only"
    },
    'email':{
        reg:/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/,
        msg:"Incorrect format ! ex : my_email_address@example.com"
    },
    'decimal':{
        reg:/^([0-9]{1,10})(,[0-9]{1,2})?$/,
        msg:"Incorrect format ! ex : 15,45"
    },
    'integer':{
        reg:/^([0-9]{1,10})$/,
        msg:"Incorrect format ! ex : 122"
    }
};
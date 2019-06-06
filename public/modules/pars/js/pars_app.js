let cat_arr = [];

function getCheckCategories(id) {

    let chbox;
    let i;
    let p;
    chbox = document.getElementById(id);
    p = cat_arr.indexOf(id);
    if (p === -1) {
        if (chbox.checked) {
            cat_arr.push(id);
        }
    } else {
        if (!chbox.checked) {
            cat_arr.splice(p, 1);
        }
    }
    console.log(cat_arr)
    return cat_arr;
}

function SetCheckActive(get_data) {
    console.log(get_data);
    $.get( "http://datascrap.bas/pars/check_act_category/"+get_data, function(get_data) {
        $( ".result" ).html(get_data );

    });

    setTimeout("location.reload()", 2000);;
}

function PostActiveCategory() {
    SetCheckActive('['+cat_arr.join(',')+']');

}

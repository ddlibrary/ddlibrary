function showHide(itself, elementId)
{
    var theElement = document.getElementById(elementId);

    if (theElement.style.display === "none") {
        theElement.style.display = "block";
    } else {
        theElement.style.display = "none";
    }

    if (itself.className.indexOf("fa-plus") == -1) {  
        itself.className += " fa-plus";
    } else { 
        itself.className = itself.className.replace(" fa-plus", " fa-minus");
    }
}

function fnTest(check, cchild){
    if($(check).is(':checked')){
        $(check).siblings('#'.cchild).find('.child').prop("checked",true);
    }else{
        $(check).siblings('#'.cchild).find('.child').prop("checked",false);        
    }
}

function changeContent(DivContent, methodUrl, parameters)
{
    $.ajax({
        type: "POST",
        url: methodUrl,
        data: "id=" + id, // appears as $_GET['id'] @ your backend side
        success: function(data) {
            // data is ur summary
            $('#'+DivContent).html(data);
        }
    });
}
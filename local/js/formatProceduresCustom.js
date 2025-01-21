BX.ready(function(){
    var addAnswer = new BX.PopupWindow("my_answer", null, {
        content: BX("ajax-add-answer"),
        closeIcon: {right: "20px", top: "10px"},
        titleBar: {content: BX.create("span", {html: "<b>Создание записи </b>", "props": {"className": "access-title-bar"}})},
        zIndex: 0,
        offsetLeft: 0,
        offsetTop: 0,
        draggable: {restrict: false},
        buttons: [
            new BX.PopupWindowButton({
                text: "Добавить",
                className: "popup-window-button-accept",
                events: {click: function(){
                        BX.ajax.submit(BX("myForm"), function(data){
                            BX( "ajax-add-answer").innerHTML = data;
                        });
                        location.href="/services/lists/23/view/0/?list_section_id=";
                    }
                }
            }),
            new BX.PopupWindowButton({
                text: "Отмена",
                className: "webform-button-link-cancel",
                events: {click: function(){
                this.popupWindow.close(); // закрытие окна
                }}
            })
            ]
    });
    var elements = document.getElementsByClassName("click_test");
    var myFunction = function() {
        var attribute = this.getAttribute("data-id");
        var doctor = this.closest('tr').getAttribute("data-id");

        BX.ajax.insertToNode("/include/formatProceduresCustomForm.php?ID="+attribute+"&DOCTOR="+doctor, BX("popup-window-content-my_answer")); // функция ajax-загрузки контента из урла в #div
        addAnswer.show(); // появление окна
    };
    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener("click", myFunction, false);
    }
});
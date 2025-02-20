BX.addCustomEvent('onTimeManwindowOpen', function(){
    document.querySelector("#timeman_main").style.visibility = "hidden";
    let a = document.createElement('div');
    let popup = BX.PopupWindowManager.create("timenan-notify", a, {
        content: BX("ajax-add-answer"),
        autoHide: true,
        closeIcon: {
            right: "20px", top:"10px",
        },
        offsetLeft: 450,
        offsetTop: 139,
        closeByEsc: true,
        overlay: {
            backgroundColor: 'black', opacity: '10'
        },
        lightShadow: true,
        darkMode: false,
        events: {
            onPopupClose: function(){
                BX.ajax({
                    url: '/local/php_interface/ajax/formControllerTimemanCustom.php',
                    method: 'POST',
                    data: {
                        action: 'DAY_END' 
                    },
                    onsuccess: function(data) {
                        alert(data);
                    }
                });
            },
        },
        buttons: [
            new BX.PopupWindowButton({
                text: "Начать рабочий день",
                className: "popup-window-button",
                events: {click: function(){
                        BX.ajax({
                            url: '/local/php_interface/ajax/formControllerTimemanCustom.php',
                            method: 'POST',
                            data: {
                                action: 'DAY_START' 
                            },
                            onsuccess: function(data) {
                                document.querySelector("#popup-window-content-timenan-notify").innerHTML = data
                            }
                        });
                    }
                }
            }),
            new BX.PopupWindowButton({
                text: "Приостановить рабочий день",
                className: "popup-window-button",
                events: {click: function(){
                        BX.ajax({
                            url: '/local/php_interface/ajax/formControllerTimemanCustom.php',
                            method: 'POST',
                            data: {
                                action: 'DAY_PAUSE' 
                            },
                            onsuccess: function(data) {
                                document.querySelector("#popup-window-content-timenan-notify").innerHTML = data
                            }
                        });
                    }
                }
            }),
            new BX.PopupWindowButton({
                text: "Проверить статус рабочего дня",
                className: "popup-window-button",
                events: {
                    click: function(){
                        BX.ajax({
                            url: '/local/php_interface/ajax/formControllerTimemanCustom.php',
                            method: 'POST',
                            data: {
                                action: 'CHECK_DAY_STATUS' 
                            },
                            onsuccess: function(data) {
                                document.querySelector("#popup-window-content-timenan-notify").innerHTML = data
                            }
                        });
                    }
                }
            })
        ]
        
    });
    popup.show();
});
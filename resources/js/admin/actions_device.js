import aquaboxApi from "../libs/AquaboxApi";

// get all actions btn
let action_btns =  document.querySelectorAll('[id^=action_]');

for( let i = 0; i < action_btns.length; i++ )
{
    action_btns[i].addEventListener("click", run_action);
}

// actions
function run_action(event) {
    let action_id = event.target.id.replace('action_', '');

    const success_callback = function(){
        new Noty({type: "success", text: "Action correctement effectuée"}).show();
    };
    const failed_callback = function(){
        new Noty({type: "error", text: "Erreur lors de l'execution de l'action"}).show();
    };
    aquaboxApi.do_action(action_id, success_callback, failed_callback);
}

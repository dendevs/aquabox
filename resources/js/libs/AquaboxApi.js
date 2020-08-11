import axios from 'axios';

// config
const aquaboxApi = axios.create({
    baseURL: process.env.MIX_APP_API_URL
});

function get_config()
{
    let config = null;

    let api_token = window.api_token;
    if( api_token )
    {
        config = {
            headers: {
                'Authorization': `Bearer ${api_token}`
            }
        };
    }

    return config;
}

// Studies
aquaboxApi.do_action = function(action_id, success_callback, failed_callback)
{
    aquaboxApi.get(`/actions/do/${action_id}`, get_config() )
        .then(res => {
            if( res.status === 200 )
            {
                // object to array
                let datas = Object.keys(res.data.data).map(function(key) {
                    return [key, res.data.data[key]];
                });

                success_callback( datas );
            }
            else
            {
                failed_callback();
            }
        })
        .catch(function (error) {
            failed_callback();
        });
};

export default aquaboxApi;

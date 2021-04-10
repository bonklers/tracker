import axios from 'axios';
import { EventBus } from './event-bus.js';

// Base URL for API Calls to the remix/evolve backend
var BASE_URL = '..'

function getUserData(username){
    /*
    getUserData returns array of user data

    url: combined URL from BASE URL and get user data api
    busPackage: data packed into an object
    Args:
        username string
    Returns:
        Object: sends busPackage and triggers getUserData Listener
    */
    window.console.log("apiInterface [getUserData] ");
    var url = BASE_URL + '/tracker-be/php/getUserData.php'
    const payload = { username: username }

    axios.post(url, payload)
        .then(response => {
            //window.console.log("[api] RESPONSE for SCHEDULE apiInterface getChargingSchedule")
            var busPackage = { data: [], status: 400 }
            if (response.data["userData"]===undefined)
            {
                window.console.log("Error, did not get user data array");
                EventBus.$emit('getUserData', busPackage);
            }
            else
            {
                busPackage = { data: response.data["userData"], status: response.data["status"]}
                EventBus.$emit('getUserData', busPackage);
            }
        })
        .catch(function (error) {
            if (error.response) {
                window.console.log("Error, did not get response");
                //window.console.log("[api] getLongTrips GET ERROR")
                //window.console.log(error.response.data);
                //window.console.log(error.response.status);
                //window.console.log(error.response.headers);
            }
        })
}

function addTrackingNumber(description, trackingNumber, username) {
    /*
    addTrackingNumber returns status number 200=success 400=fail

    url: combined URL from BASE URL and get user data api
    busPackage: data packed into an object

    Returns:
        Object: sends busPackage and triggers addTrackingNumber Listener
    */

    window.console.log("apiInterface [addTrackingNumber] ");
    var url = BASE_URL + '/tracker-be/php/addTrackingNumber.php'
    const payload = { description: description, trackingNumber: trackingNumber, username: username}

    axios.post(url, payload)
        .then(response => {
            //window.console.log("[api] RESPONSE for SCHEDULE apiInterface getChargingSchedule")
            if (response.data === undefined) {
                window.console.log("Error, did not get addTackingNumber response");
                // TODO: show error?
            }
            else if (response.data == 400)
            {
                window.console.log("Error, got addTackingNumber error");
                // TODO: show error?
            }
            getUserData(username)
        })
        .catch(function (error) {
            if (error.response) {
                window.console.log("Error, did not get response");
                //window.console.log("[api] getLongTrips GET ERROR")
                //window.console.log(error.response.data);
                //window.console.log(error.response.status);
                //window.console.log(error.response.headers);
            }
        })
}

function uploadImage(selectedItem, image, username) {
    /*
    uploadImage returns status number 200=success 400=fail

    url: combined URL from BASE URL and get user data api
    busPackage: data packed into an object

    Returns:
        Object: sends busPackage and triggers uploadImage Listener
    */
    var url = '/tracker-be/php/uploadImage.php'
    let data = new FormData();
    data.append('name', 'my-picture');
    data.append('file', image.target.files[0]);


    let config = {
        params: { id: selectedItem.id },
        header: { 'Content-Type': 'multipart/form-data' },
        baseURL: BASE_URL,
    }

    axios.post(
        url,
        data,
        config
    ).then(
        response => {
            //TODO: handle error if response is not 200
            console.log('image upload response > ', response)

            getUserData(username)
        }
    )

    // var formData = new FormData();
    // // var imagefile = document.querySelector('#file');
    // formData.append("image", image.target.files[0]);
    // axios.post(url, formData, {
    //     headers: {
    //         'Content-Type': 'multipart/form-data'
    //     }
    // })

    
}

// export all functions to make them usable for other files
export default { getUserData, addTrackingNumber, uploadImage}
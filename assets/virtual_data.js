function getVirtualData() {
    return jQuery.ajax({
        type: "POST",
        url: "assets/virtual_data.php",
        dataType: "json",
        data: {
            //arguments: [1, 2],
            functionname: "getSingleDataPoint"
        },
        success: function (obj, statusText) {
            if (!('error' in obj)) {
                //document.getElementById("out").innerHTML = obj.result["lat"];
                //console.log("SUCCESS");
                //return obj;
            } else {
                //console.log(obj.error);
                //console.log("PHP ERROR: \n" + JSON.stringify(obj.error));
                //document.getElementById("out").innerHTML = "PHP ERROR: \n" + JSON.stringify(obj.error);
                //return null;
            }
        },
        error: function (error) {
            //console.log(error);
            //console.log("JQUERY ERROR: \n" + JSON.stringify(error));
            //document.getElementById("out").innerHTML = "JQUERY ERROR: \n" + JSON.stringify(error);
            //return null;
        }
    });
}

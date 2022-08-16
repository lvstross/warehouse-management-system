/**
 * Convert the invoice json strings to json
 *
 * @param data | invoice object
 * @return object
 */
export const invoiceLoop = (data) => {
    for(var key in data){
        if(key == 'date'){
            data[key] = invertDate(data[key]);
        }
        if(key === 'customer'){
            var cust = data[key];
            if(typeof cust == 'string'){
                while(typeof cust == 'string'){
                    cust = JSON.parse(cust);
                }
                data[key] = cust;
            }
        } else if (key === 'line_items'){
            var line = data[key];
            if(typeof line == 'string'){
                while(typeof line == 'string'){
                    line = JSON.parse(line);
                }
                data[key] = line;
            }
        }
    }
    return data;
}

/**
 * Invert date string
 *
 * @param date | String
 * @return String
 */
export const invertDate = (date) => {
    if(date){
        let d = date.split('-');
        let month = d[1];
        if(month[0] == 0){
            month = month[1];
        }
        date = [month,d[2],d[0]].join('-');
        return date;
    }
    return;
}

/**
 * Convert rgb color to hex
 * @return String
 */
export const rgb2hex = (rgb) => {
 rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
 return (rgb && rgb.length === 4) ?
  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
}

/**
 * Convert Military time to standard time
 *
 * @return timeValue | String
 */
export const convertMilitaryTime = () => {
    var time = getNewTime();
    time = time.split(':');
    var hours = Number(time[0]);
    var minutes = Number(time[1]);
    var seconds = Number(time[2]);

    var timeValue;
    if (hours > 0 && hours <= 12){
      timeValue= "" + hours;
    } else if (hours > 12) {
      timeValue= "" + (hours - 12);
    } else if (hours == 0) {
      timeValue= "12";
    }

    timeValue += (minutes < 10) ? ":0" + minutes : ":" + minutes;  // get minutes
    // timeValue += (seconds < 10) ? ":0" + seconds : ":" + seconds;  // get seconds
    timeValue += (hours >= 12) ? " P.M." : " A.M.";  // get AM/PM

    return timeValue;
}

/**
 * Get the current date with the month, day and year
 *
 * @return 'month/day/year' | String
 */
export const getDate = () => {
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();
    return month+'/'+day+'/'+year;
}

/**
 * Get the current date string to be stored in db
 *
 * @return 'year/month/day' | String
 */
export const getDateStr = () => {
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let day = date.getDate();
    return year+'-'+month+'-'+day;
}

/**
 * Get the current time with the hour, minutes and seconds
 *
 * @return 'hour:minutes:seconds' | String
 */
export const getNewTime = () => {
    let date = new Date();
    let minutes = date.getMinutes();
    let hour = date.getHours();
    let seconds = date.getSeconds();
    return String(hour+':'+minutes+':'+':'+seconds);
}

/**
 * Add beforeunload event listener to window
 *
 * @return void
 */
export const stopPageUnload = () => {
    window.addEventListener("beforeunload", function (e) {
      var confirmationMessage = "\o/";

      e.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
      return confirmationMessage;              // Gecko, WebKit, Chrome <34
    }, true);
}

/**
 * Add beforeunload event listener to window
 *
 * @return void
 */
export const allowPageUnload = () => {
    window.removeEventListener("beforeunload", function (e) {
      var confirmationMessage = "\o/";

      e.returnValue = confirmationMessage;     // Gecko, Trident, Chrome 34+
      return confirmationMessage;              // Gecko, WebKit, Chrome <34
    }, true);
}

/**
 * Get URI Parameter value
 * out of http://dataentry.com?app=value will return 'value'
 * out of the whole string
 *
 * @return String
 */
export const getUriParam = () => {
    if(window.location.pathname != '/submitrouter'){
        return window.location.href.split('?')[1].split('=')[1];
    }
}

/**
 * Opens the mains container
 */
export const openContainer = () => {
    let cont = document.getElementById('main-container').classList;
    if(cont.contains('container')){
        cont.remove('container');
        cont.add('container-fluid');
    }
}

/**
 * Opens the mains container
 */
export const closeContainer = () => {
    let cont = document.getElementById('main-container').classList;
    if(cont.contains('container-fluid')){
        cont.remove('container-fluid');
        cont.add('container');
    }
}

export default {
    invoiceLoop,
    invertDate,
    rgb2hex,
    convertMilitaryTime,
    getDate,
    getDateStr,
    getNewTime,
    stopPageUnload,
    allowPageUnload,
    getUriParam,
    openContainer,
    closeContainer
}

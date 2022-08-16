function checkSessionStatus(){
    console.log('Interval has started');
    setInterval(()=>{
        axios({
            method: 'get',
            url: 'api/user',
            validateStatus(status) {
                if(status >= 200 && status < 300){
                    console.log(status);
                    return status;
                }else if(status == 401){
                    alert("Your session has timed out. You will now be redirected.");
                    window.location = window.location.origin + '/login';
                }
            }
        });
    }, 1000 * 60 * 60 * 2 + 2000); // 2 hours and 2 seconds
}
checkSessionStatus();
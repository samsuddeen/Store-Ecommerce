// Enable pusher logging - don't include this in production
let permission = Notification.requestPermission();
// Pusher.logToConsole = true;
var pusher = new Pusher('5535e4c225f268043ffd', {
    cluster: 'ap2'
});



var channel = pusher.subscribe('to-seller.'+window.user_id);
channel.bind('pusher:subscription_succeeded', function (members) {
    console.log("Subscription Success");
});

channel.bind('App\\Providers\\NotificationCreated', function (data) {
    console.log(data);
    if (Notification.permission === "granted") {
        const greeting = new Notification(data.data.title, {
            body: data.data.summary,
        });
        greeting.onclick = () => window.open(data.data.url);
    } else {
        swal({
            title: data.data.title,
            text: data.data.summary,
            icon: "success",
        });
    }
});
// Enable pusher logging - don't include this in production
let permission = Notification.requestPermission();
// Pusher.logToConsole = true;





var pusher = new Pusher('5535e4c225f268043ffd', {
    cluster: 'ap2'
});



var channel = pusher.subscribe('to-customer.'+window.user_id);
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

var channel1 = pusher.subscribe('push-notification-to-customer.0');
channel1.bind('pusher:subscription_succeeded', function (members) {
    console.log("Subscription Success channel 1");
});
channel1.bind('App\\Events\\PushNotificationEvent', function (data) {
    console.log(data);
    if (Notification.permission === "granted") {
        const greeting = new Notification(data.psuhNotification.title, {
            body: data.psuhNotification.summary,
        });
        greeting.onclick = () => window.open(data.psuhNotification.url);
    } else {
        swal({
            title: data.psuhNotification.title,
            text: data.psuhNotification.summary,
            icon: "success",
        });
    }
});


var channel1 = pusher.subscribe('push-notification-to-customer.'+window.user_id);
channel1.bind('pusher:subscription_succeeded', function (members) {
    console.log("Subscription Success channel 2");
});
channel1.bind('App\\Events\\PushNotificationEvent', function (data) {
    console.log(data);
    if (Notification.permission === "granted") {
        const greeting = new Notification(data.psuhNotification.title, {
            body: data.psuhNotification.summary,
        });
        greeting.onclick = () => window.open(data.psuhNotification.url);
    } else {
        swal({
            title: data.psuhNotification.title,
            text: data.psuhNotification.summary,
            icon: "success",
        });
    }
});

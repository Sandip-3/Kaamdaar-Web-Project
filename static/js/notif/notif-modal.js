function updateNotifCount()
{
    let countContainer = document.getElementById("notif-count");
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if(xhr.readyState == XMLHttpRequest.DONE)
        {
            if(xhr.status == 200)
            {
                var count = JSON.parse(xhr.responseText)?.notif_count;
                if(!count || count == "0")
                    countContainer.style.display = "none";
                else 
                {
                    countContainer.style.display = "block";
                    countContainer.innerText = count;
                }
            }
        }
    }

    xhr.open("GET", "./modal/fetch-notifs.php?count=9999999999&action=count", true);
    xhr.send();

    setInterval(() => { 
        xhr.open("GET", "./modal/fetch-notifs.php?count=9999999999&action=count", true);
        xhr.send();
    }, 10000);
};

function showNotificationModal()
{
    let modal = document.getElementById("notif-modal");
    modal.style.display = "block";

    let notifList = document.getElementsByClassName("notif-list")[0];

    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if(xhr.readyState == XMLHttpRequest.DONE)
        {
            if(xhr.status == 200)
            {
                const notifications = JSON.parse(xhr.responseText);
                if(notifications.length < 1)
                {
                    let noNotifMsg = document.getElementsByClassName("no-notif-msg")[0];
                    noNotifMsg.style.display = "block";
                }

                for(const notification of notifications)
                {
                    const notifModel = {
                        icon: notification.ICON,
                        profile: notification.PROFILE,
                        title: notification.TITLE,
                        description: notification.DESCRIPTION,
                        time: timeSince(new Date(notification.TIME)) + " ago",
                        status: notification.STATUS,
                        requestId: notification.REQUEST_ID
                    }

                    let notifItem = document.createElement("li");
                    notifItem.classList.add("notif");

                    const notifType = notification.NOTIF_TYPE;
                    if(notifType === "REQUEST")
                    {
                        notifModel.userId = notification.USER_ID;
                        let notif = new RequestNotification(
                            notifModel.icon,
                            notifModel.profile,
                            notifModel.title,
                            notifModel.description,
                            notifModel.time,
                            notifModel.requestId,
                            notifModel.status,
                            notifModel.userId
                        );
                        notifItem.appendChild(notif.createHTML());
                    }
                    else if(notifType === 'RESPONSE')
                    {
                        notifModel.sender = notification.SENDER;
                        const responseType = notification.RESPONSE_TYPE;
                        let notif = new ResponseNotification(
                            notifModel.icon,
                            notifModel.profile,
                            notifModel.title,
                            notifModel.description,
                            notifModel.time,
                            notifModel.requestId,
                            "", // response type
                            notifModel.status,
                            notifModel.sender
                        );
                        if(responseType === "user-response")
                            notif.responseType = "user-response";
                        else if(responseType === "business-response")
                            notif.responseType = "business-response";

                        notifItem.appendChild(notif.createHTML());
                    }

                    notifList.appendChild(notifItem); 
                }
            }
        }
    }

    xhr.open("GET", "./modal/fetch-notifs.php?count=1000000000&action=fetch", true);
    xhr.send();

    window.onclick = (event) => {
        if(event.target == modal)
        {
            modal.style.display = "none";
            while(notifList.firstChild)
            {
                notifList.removeChild(notifList.firstChild);
            }
        }
    };
}

function timeSince(date) {
    var seconds = Math.floor((new Date() - date) / 1000);
    var interval = seconds / 31536000;

    if (interval > 1) 
        return Math.floor(interval) + " years";

    interval = seconds / 2592000;
    if (interval > 1)
        return Math.floor(interval) + " months";

    interval = seconds / 86400;
    if (interval > 1)
        return Math.floor(interval) + " days";

    interval = seconds / 3600;
    if (interval > 1)
        return Math.floor(interval) + " hours";

    interval = seconds / 60;
    if (interval > 1)
        return Math.floor(interval) + " minutes";

    return Math.floor(seconds) + " seconds";
}
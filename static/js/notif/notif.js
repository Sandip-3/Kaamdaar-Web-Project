class Notification 
{
    constructor(icon, profile, title, description, date, requestId)
    {
        this.icon = icon;
        this.profile = profile;
        this.title = title;
        this.description = description;
        this.date = date;
        this.requestId = requestId;
    }

    createHTML()
    {
        let notifRoot = document.createElement("div");
        notifRoot.classList.add("notif-root", "notif-unread");

        let notifHead = document.createElement("div");
        notifHead.classList.add('notif-head');

        let notifHeadIcon = document.createElement("div");
        notifHeadIcon.classList.add('notif-head-icon');

        let notifHeadIconImg = document.createElement("img");
        notifHeadIconImg.classList.add('notif-head-icon-img');
        notifHeadIconImg.src = this.icon;

        let notifHeadTitle = document.createElement("div");
        notifHeadTitle.classList.add('notif-head-title');

        let notifProfile = document.createElement("p");
        notifProfile.classList.add('notif-profile');
        notifProfile.innerText = this.profile;

        let notifTitle = document.createElement("p");
        notifTitle.classList.add('notif-title');
        notifTitle.innerText = this.title;

        let notifBody = document.createElement("div");
        notifBody.classList.add('notif-body');

        let notifDesc = document.createElement("p");
        notifDesc.classList.add('notif-desc');
        notifDesc.innerText = this.description;

        let notifDate = document.createElement("p");
        notifDate.classList.add('notif-date');
        notifDate.innerText = this.date;

        notifHeadIcon.appendChild(notifHeadIconImg);
        notifHeadTitle.appendChild(notifProfile);
        notifHeadTitle.appendChild(notifTitle);
        notifHead.appendChild(notifHeadIcon);
        notifHead.appendChild(notifHeadTitle);

        notifBody.appendChild(notifDesc);
        notifBody.appendChild(notifDate);

        notifRoot.appendChild(notifHead);
        notifRoot.appendChild(notifBody);
        return notifRoot;
    }
}

class RequestNotification extends Notification
{
    constructor(icon, profile, title, description, date, requestId, requestStatus, userId)
    {
        super(icon, profile, title, description, date, requestId);
        this.userId = userId;
        this.requestStatus = requestStatus;
    }

    createHTML()
    {
        let notif = super.createHTML();
        if(this.requestStatus == "0") // 0 means request is pending
        {
            let actionBar = document.createElement("div");
            actionBar.classList.add('notif-action-bar');

            let offer = document.createElement('button');
            offer.innerText = "Offer service";
            offer.classList.add('notif-btn', "notif-offer-btn");

            offer.addEventListener("click", () => {
                console.log(this.userId);
                let xhr = new XMLHttpRequest();
                xhr.open("GET", `./offer-service.php?req-id=${this.requestId}&rec-id=${this.userId}`, true);
                xhr.send();
            });

            actionBar.appendChild(offer);
            notif.appendChild(actionBar);
        }
        return notif;
    }
}

class ResponseNotification extends Notification
{
    constructor(icon, profile, title, description, date, requestId, type, status, sender)
    {
        super(icon, profile, title, description, date, requestId);
        this.responseType = type;
        this.responseStatus = status;
        this.senderId = sender;
    }

    createHTML()
    {
        let notif = super.createHTML();
        let actionBar = document.createElement("div");
        actionBar.classList.add('notif-action-bar');

        if(this.responseType === "business-response")
        {
            let viewDetails = document.createElement('button');
            viewDetails.innerText = "View details";
            viewDetails.classList.add('notif-btn', 'notif-view-details-btn');

            if(this.responseStatus == "0") // if response is interactable
                actionBar.appendChild(viewDetails);
        }
        else if(this.responseType === "user-response")
        {
            let accept = document.createElement("button");
            accept.innerText = "Accept";
            accept.classList.add('notif-btn', 'notif-accept-btn');

            let reject = document.createElement("button");
            reject.innerText = "Reject";
            reject.classList.add('notif-btn', 'notif-reject-btn');

            if(this.responseStatus == "0") // if response is interactable
            {
                actionBar.appendChild(reject);
                actionBar.appendChild(accept);

                const offerResponse = (response) => {
                    let responseStatusId;
                    if(response === "reject")
                        responseStatusId = 1;
                    else if(response === "accept")
                        responseStatusId = 0;

                    let url = `./offer-response.php?rec-id=${this.senderId}&req-id=${this.requestId}&response-status=${responseStatusId}`;
                    let xhr = new XMLHttpRequest();
                    xhr.open("GET", url, true);
                    xhr.send();
                }

                accept.addEventListener("click", () => offerResponse("accept"));
                reject.addEventListener("click", () => offerResponse("reject"));
            }
        }

        notif.appendChild(actionBar);
        return notif;
    }
}

/*
offer.addEventListener("click", () => {
                    $.ajax({
                        url: `./offer-service.php?req-id=${data.requestId}&rec-id=${data.userId}`,
                        success: () => {
                            console.log("OK");
                        },
                        error: () => {
                            console.log("bad");
                        }
                    });
                });

                function acceptRejectOffer(data, action)
            {
                
            }
*/
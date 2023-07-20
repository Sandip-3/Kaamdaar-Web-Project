<?php 
$type = $_GET['reqtype'];
?>

<style>
    .modal-content {
        position: fixed;
        bottom: 0;
        background-color: #fefefe;
        width: 100%;
        height: 300px;
        -webkit-animation-name: slideIn;
        -webkit-animation-duration: 0.4s;
        animation-name: slideIn;
        animation-duration: 0.4s;
    }

    @-webkit-keyframes slideIn {
        from {bottom: -300px; opacity: 0} 
        to {bottom: 0; opacity: 1}
    }

    @keyframes slideIn {
        from {bottom: -300px; opacity: 0}
        to {bottom: 0; opacity: 1}
    }

    .req-info
    {
        width: 100%;
        height: 100px;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        padding-left: 20px;
    }

    .req-info *
    {
        box-sizing: border-box;
    }

    .req-type
    {
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
    }

    .req-type:nth-child(2)
    {
        margin-top: 20px;
    }

    .req-icon 
    {
        width: 100px;
        height: 100px;
        border-radius: 50%;
    }

    .req-icon-img 
    {
        width: 100px;
        height: 100px;
        position: absolute;
        left:32%;
    }

    .req-name
    {
        font-size: 1.2em;
        font-weight: bold;
    }

    .req-loc 
    {
        font-size: 1em;
        font-weight: 500;
    }

    .req-send-btn
    {
        width: 300px;
        height: 40px;
        background-color: rgb(255, 185, 70);
        border: none;
        border-radius: 8px;
        font-size: 1em;
        position: relative;
        left:38%;
        margin-bottom:8px;
    }

    .user-info
    {
        padding-left: 20px;
        margin-top: 20px;
        border-radius: 8px;
        width: auto;
        background-color: #DAE0E6;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .1), 0 2px 4px rgba(0, 0, 0, .1);
    }
        
    #user-detail{
        position: absolute;
        left:40%;
        padding-top:10px;
        padding-bottom:10px;
    }

    .user-info span 
    {
        padding:4px;
        font-size:25px;
    }

    ul 
    {
        padding-left:60px;
        padding:10px;
        font-size:20px;
    }

    .req-send 
    {
        margin-top: 20px;
        padding-left: 20px;
    }
</style>

<div class="modal-content">
    <div class="modal-header"></div>
    <div class="modal-body">
        <div class="req-info">
            <div class="req-type">
                <div class="req-icon">
                    <img class="req-icon-img" src="https://img.icons8.com/color/344/computer-support.png" alt="Icon">
                </div>
                <div id="user-detail">
                    <div class="req-name">
                        <p>Computer Repair</p>
                    </div>
                    <div class="req-loc">
                        <p>
                            Near Sankhamul
                        </p>
                    </div>
                </div> 
            </div>
        </div>
        <div class="user-info">
            <span>These informations will be visible to your nearest [Service] providers.</span>
            <ul>
                <li>
                    Your name
                </li>
                <li>
                    The location you just chose
                </li>
                <li>
                    Your phone number
                </li>
            </ul>
        </div>
        <div class="req-send">
            <button id="req-send-btn" class="req-send-btn" onclick ="location.href='requests.php'">Send request</button>
        </div>
    </div>
</div>

<script defer>
    var sendButton = undefined;
    window.onload = function() {
        sendButton = document.getElementById("req-send-btn");
        sendButton.addEventListener("click", (e) => addNewRequest());
    }

    function addNewRequest()
    {
        if(!sendButton) return;

        sendButton.disabled = true;

        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if(xhttp.readyState == XMLHttpRequest.DONE)
            {
                if(xhttp.status != 200)
                    sendButton.disabled = false;
            }
        };

        const lng = longlat.lng;
        const lat = longlat.lat;
        const url = `./insert-request.php?type=<?php echo $type; ?>&loc=${document.querySelector('.req-loc p').innerText}&lat=${lat}&lng=${lng}`;
        xhttp.open("GET", url, true);
        xhttp.send();
    }
</script>
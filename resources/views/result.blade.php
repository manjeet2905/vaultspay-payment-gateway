<h2 id="status">Verifying payment...</h2>
<div id="result">Please wait</div>

<script>
const ref = "{{ $reference }}";

async function checkStatus(){
    let res = await fetch('/vaultspay/status/'+ref);
    let data = await res.json();

    if(data.status === 'PENDING'){
        setTimeout(checkStatus,2000);
    }else{
        document.getElementById('result').innerHTML =
        "<pre>"+JSON.stringify(data,null,2)+"</pre>";
        document.getElementById('status').innerHTML =
        "status: "+JSON.stringify(data.status)+"";
    }
}
checkStatus();
</script>

const abi = [
	{
	 "inputs": [],
	 "stateMutability": "nonpayable",
	 "type": "constructor"
	},
	{
	 "inputs": [],
	 "name": "getLastTransferTime",
	 "outputs": [
	  {
	   "internalType": "uint256",
	   "name": "hour",
	   "type": "uint256"
	  },
	  {
	   "internalType": "uint256",
	   "name": "minute",
	   "type": "uint256"
	  },
	  {
	   "internalType": "uint256",
	   "name": "second",
	   "type": "uint256"
	  }
	 ],
	 "stateMutability": "view",
	 "type": "function"
	},
	{
	 "inputs": [
	  {
	   "internalType": "uint256",
	   "name": "index",
	   "type": "uint256"
	  }
	 ],
	 "name": "getTransfer",
	 "outputs": [
	  {
	   "internalType": "address",
	   "name": "from",
	   "type": "address"
	  },
	  {
	   "internalType": "address",
	   "name": "to",
	   "type": "address"
	  },
	  {
	   "internalType": "uint256",
	   "name": "value",
	   "type": "uint256"
	  },
	  {
	   "internalType": "uint256",
	   "name": "time",
	   "type": "uint256"
	  }
	 ],
	 "stateMutability": "view",
	 "type": "function"
	},
	{
	 "inputs": [],
	 "name": "lastTransfer",
	 "outputs": [
	  {
	   "internalType": "address",
	   "name": "",
	   "type": "address"
	  }
	 ],
	 "stateMutability": "view",
	 "type": "function"
	},
	{
	 "inputs": [],
	 "name": "lastTransferTime",
	 "outputs": [
	  {
	   "internalType": "uint256",
	   "name": "",
	   "type": "uint256"
	  }
	 ],
	 "stateMutability": "view",
	 "type": "function"
	},
	{
	 "inputs": [],
	 "name": "lastValue",
	 "outputs": [
	  {
	   "internalType": "uint256",
	   "name": "",
	   "type": "uint256"
	  }
	 ],
	 "stateMutability": "view",
	 "type": "function"
	},
	{
	 "inputs": [],
	 "name": "owner",
	 "outputs": [
	  {
	   "internalType": "address",
	   "name": "",
	   "type": "address"
	  }
	 ],
	 "stateMutability": "view",
	 "type": "function"
	},
	{
	 "inputs": [
	  {
	   "internalType": "address",
	   "name": "_to",
	   "type": "address"
	  },
	  {
	   "internalType": "uint256",
	   "name": "_value",
	   "type": "uint256"
	  }
	 ],
	 "name": "transfer",
	 "outputs": [],
	 "stateMutability": "nonpayable",
	 "type": "function"
	},
	{
	 "inputs": [],
	 "name": "transferCount",
	 "outputs": [
	  {
	   "internalType": "uint256",
	   "name": "",
	   "type": "uint256"
	  }
	 ],
	 "stateMutability": "view",
	 "type": "function"
	},
	{
	 "inputs": [
	  {
	   "internalType": "uint256",
	   "name": "",
	   "type": "uint256"
	  }
	 ],
	 "name": "transfers",
	 "outputs": [
	  {
	   "internalType": "address",
	   "name": "to",
	   "type": "address"
	  },
	  {
	   "internalType": "uint256",
	   "name": "value",
	   "type": "uint256"
	  },
	  {
	   "internalType": "uint256",
	   "name": "time",
	   "type": "uint256"
	  }
	 ],
	 "stateMutability": "view",
	 "type": "function"
	}
   ];
const contractAddress = '0xd1CB4DcDf777d7d0493C35491b9A495A55Cd9fee'; // TODO: add address of MeritBox contract
const provider = new ethers.providers.JsonRpcProvider("HTTP://127.0.0.1:7545"); // TODO: add RPC endpoint URL
const contract = new ethers.Contract(contractAddress, abi, provider);

const transferRows = document.getElementById('transfer-rows');
let lastTransferCount = 0;

async function displayTransfers() {
    const transferCount = await contract.transferCount();
    if (transferCount === 0) {
        const row = document.createElement('tr');
        const noTransfersCell = document.createElement('td');
        noTransfersCell.colSpan = 4;
        noTransfersCell.innerText = "沒有轉移記錄";
        row.appendChild(noTransfersCell);
        transferRows.appendChild(row);
        return;
    }
    for (let i = 0; i < transferCount; i++) {
        if (i < lastTransferCount) {
            break;
        }
        const [from, to, value, timestamp] = await contract.getTransfer(i);
        const date = new Date(timestamp * 1000);
        const row = document.createElement('tr');
        const fromCell = document.createElement('td');
        const toCell = document.createElement('td');
        const valueCell = document.createElement('td');
        const timeCell = document.createElement('td');
        //const space = '\u00A0'; // 三个不间断空格
        //const spaceNode = document.createTextNode(space);
        
        fromCell.innerText = from;
        toCell.innerText = to;
        valueCell.innerText = ethers.utils.formatEther(value);
        timeCell.innerText = date.toLocaleString();

        row.appendChild(toCell);
        row.appendChild(timeCell);
        transferRows.insertBefore(row, transferRows.firstChild);
    }
    lastTransferCount = transferCount;
}


displayTransfers();
   setInterval(() => {
    location.reload();
   }, 10000)

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Upload Bank Transactions</title>
    <meta name="description" content="Interview Task - Upload Bank Transactions">
    <meta name="author" content="Jiaqi">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<style>
    .lds-ring {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }
    .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 64px;
        height: 64px;
        margin: 8px;
        border: 8px solid #fff;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #fff transparent transparent transparent;
    }
    .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
    }
    .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
    }
    .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
    }
    @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    .modal {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: grey;
        opacity: 0.8;
        display: none;
    }
    .modal.in {
        display: unset !important;
    }
    .modal > .modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .modal > .modal-content > #msg {
        font-size: 20px;
        color: white;
    }
    .modal > .modal-content > #close-modal-btn {
        font-size: 20px;
        color: white;
        display: none;
        cursor: pointer;
        margin-top: 20px;
    }
    #transaction-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    #transaction-table > thead tr th {
        border-bottom: 2px solid lightgray;
        padding: 10px 0;
    }
    #transaction-table > #table-content tr td {
        padding: 10px 0;
    }
    #transaction-table > #table-content tr:nth-child(2n+1) td {
        background-color: #f3f3f3;
    }
</style>

<body>
    <div id="csv-uploader">
        <p style="font-size: 20px">Upload new CSV</p>
        <p>Select CSV to upload:</p>
        <div>
            <input name="file" type="file" accept=".csv" />
        </div>
        <button id="upload-btn" type="button">Upload CSV</button>
        <div class="modal">
            <div class="modal-content">
                <div class="lds-ring">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div id="msg"></div>
                <div id="close-modal-btn">Close</div>
            </div>
        </div>
        <p style="font-size: 24px">Bank Transactions from CSV</p>
        <table id="transaction-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Transaction Code</th>
                    <th>Valid Transaction?</th>
                    <th>Customer Number</th>
                    <th>Reference</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="table-content"></tbody>
        </table>
    </div>

    <script type="text/javascript">
        var uploader = csvUploder('#csv-uploader');

        uploader.find('#upload-btn').on('click', function() {
            var file = uploader.find('input[name="file"]')[0].files[0];
            if (!file) {
                alert('Please choose file');
                return false;
            }

            var self = $(this);
            var formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'upload_csv');

            self.html('Uploading...').prop('disabled', true);
            uploader.x.showModal('Uploading & Processing...', false);

            $.ajax({
                url: 'ajax_handler.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    res = JSON.parse(res);
                    if (res.success) {
                        uploader.x.renderTable(res.data);
                        uploader.x.hideModal();
                        self.html('Upload CSV').prop('disabled', false);
                        return;
                    }
                    uploader.x.showModal('Something went wrong!', true);
                },
                error: function(error) {
                    uploader.x.showModal("Sorry! Couldn't process your request.", true);
                    self.html('Upload CSV').prop('disabled', false);
                }
            });
        });

        function csvUploder (selector) {
            var scope = $(selector);
            var modal = scope.find('.modal');
            var msgDiv = modal.find('#msg');
            var closeBtn = modal.find('#close-modal-btn');
            
            scope.x = {
                showModal: function(msg, showCloseBtn) {
                    msgDiv.html(msg);
                    modal.addClass('in');
                    if(showCloseBtn){
                        closeBtn.show();
                        return;
                    }
                    closeBtn.hide();
                },
                hideModal: function() {
                    msgDiv.html('');
                    modal.removeClass('in');
                    closeBtn.hide();
                },
                renderTable: function(data) {
                    var content = '';
                    data.forEach(transaction => {
                        content += '<tr>'
                            + '<td>' + transaction['dateTime'] + '</td>'
                            + '<td>' + transaction['transactionCode'] + '</td>'
                            + '<td>' + (transaction['codeValid'] ? 'Yes' : 'No') + '</td>'
                            + '<td>' + transaction['customerNumber'] + '</td>'
                            + '<td>' + transaction['reference'] + '</td>'
                            + '<td style="color: ' + (transaction['isDebit'] ? 'red' : 'green') + '">' + transaction['amount'] + '</td>'
                            + '</tr>';
                    });
                    scope.find('#table-content').html(content);
                }
            }

            closeBtn.on('click', function() {
                $(this).hide();
                scope.x.hideModal();
            });

            return scope;
        }
    </script>
</body>
</html>
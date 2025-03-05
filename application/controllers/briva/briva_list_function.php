function createva()
{
    curl --location 'https://sandbox.partner.api.bri.co.id/snap/v1.0/transfer-va/create-va' \
    --header 'X-EXTERNAL-ID: 2213' \
    --header 'CHANNEL-ID: BAPE' \
    --header 'X-SIGNATURE: 5151d88679102d02c6a564223d5547e066e0a1f4187a95bec10399a2ec30afbf81103790ab57c9997cd94b4f30e43aacba7b91d1933cefcefb5f11410fa35133' \
    --header 'X-TIMESTAMP: 2025-03-05T10:38:15.134+07:00' \
    --header 'X-PARTNER-ID: kiuonline' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer SXWihuYBX5eGs97i2I2IlPGXNVlp' \
    --data '{
        "institutionCode": "kiuonline",
        "partnerServiceId": "   22123",
        "customerNo": "6282264054784",
        "virtualAccountNo": "   221236282264054784",
        "virtualAccountName": "customerumum1",
        "totalAmount": {
            "value": "10000.00",
            "currency": "IDR"
        },
        "expiredDate": "2025-03-05T22:54:00+07:00",
        "trxId": "MQS5325186923",
        "additionalInfo": {
            "description": "Invoice#MQS5325186923 - Karisma Online"
        }
    }'
}

function 
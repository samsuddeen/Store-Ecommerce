<?php
// In your controller method
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PayWithKhalti
{

    public function pay(Request $request)
    {
        // Set up the Guzzle client to send the request
        $client = new Client([
            'base_uri' => 'https://api.khalti.com/',
            'headers' => [
                'Authorization' => 'Key YOUR_SECRET_KEY_HERE',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        // Prepare the request data
        $data = [
            'mobile' => $request->mobile,
            'amount' => $request->amount,
            'product_name' => 'Order #' . $request->order_id,
            'product_identity' => $request->order_id,
            'product_url' => 'http://yourwebsite.com/orders/' . $request->order_id,
        ];

        // Send the request to Khalti's ePayment API
        $response = $client->post('v2/payment/initialize', [
            'json' => $data,
        ]);

        // Process the response
        $responseBody = json_decode($response->getBody(), true);
        if ($responseBody['state']['name'] == 'Initialized') {
            // Redirect the user to Khalti's payment page
            return redirect($responseBody['data']['url']);
        } else {
            // Handle the error
            return redirect()->back()->with('error', 'Failed to initiate payment');
        }
    }
}

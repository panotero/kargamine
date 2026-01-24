<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DynamicMailerService;
use App\Models\MailerSetting;
use Illuminate\Support\Facades\Log;
use App\Mail\GenericMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Services\ApplicationMailer;


class MailerController extends Controller
{
    public function index()
    {
        $config = MailerSetting::latest()->first();
        return view('mailer.index', compact('config'));
    }

    public function save(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mail_mailer' => [
                'required',
                'string',

            ],
            'mail_host' => [
                'required',
                'string',

            ],
            'mail_port' => [
                'required'
            ],
            'mail_username' => [
                'required',
                'string',

            ],
            'mail_password' => [
                'required',
                'string',

            ],
            'mail_encryption' => [
                'required',
                'string',

            ],
            'mail_from_address' => [
                'required',
                'email'
            ],
            'mail_from_name' => [
                'required',
                'string',

            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        try {
            $validated = $request->validate([
                'mail_mailer' => 'required|string',
                'mail_host' => 'required|string',
                'mail_port' => 'required',
                'mail_username' => 'required|string',
                'mail_password' => 'required|string',
                'mail_encryption' => 'required|string',
                'mail_from_address' => 'required|email',
                'mail_from_name' => 'required|string',
            ]);

            MailerSetting::updateOrCreate([], $validated);

            return back()->with('success', 'Mailer configuration saved successfully!');
        }

        //enable this for log debuging
        // catch (\Illuminate\Validation\ValidationException $e) {
        //     \Log::error('Validation failed', $e->errors());
        //     throw $e;
        // }
        catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // Example API endpoint for sending mail
    public function send(Request $request, DynamicMailerService $mailer)
    {

        $validator = Validator::make($request->all(), [
            'to' => [
                'nullable',
                'email'
            ],
            'subject' => [
                'nullable',
                'string',

            ],
            'title' => [
                'nullable',
                'string',

            ],
            'body' => [
                'nullable',
                'string',

            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input detected.',
                'invalid_fields' => $validator->errors(),
            ], 422);
        }

        try {
            Log::channel('mail_logs')->info('📩 Test mail send requested', [
                'input' => $request->all(),
            ]);

            $validated = $request->validate([
                'to' => 'nullable|email',
                'subject' => 'nullable|string',
                'title' => 'nullable|string',
                'body' => 'nullable|string',
            ]);

            $mailArray = [
                'message' => $validated['body'],
                'title' => $validated['title'],
            ];

            //  Use Mailable instead of raw body
            Mail::to($validated['to'])->send(
                new GenericMail($validated['subject'], $validated['body'], $mailArray)
            );

            Log::channel('mail_logs')->info(' Mail send result', [
                'to'        => $validated['to'],
                'subject'   => $validated['subject'],
                'success'   => true,
                'timestamp' => now()->toDateTimeString(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mail sent successfully!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('mail_logs')->error('❌ Validation failed during test mail send', [
                'errors' => $e->errors(),
                'input'  => $request->all(),
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::channel('mail_logs')->error('💥 Unexpected error during test mail send', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unexpected error while sending mail.',
            ], 500);
        }
    }
    public function test(ApplicationMailer $mailer)
    {
        $mailer->send(
            [
                'to'      => 'panoterominton@gmail.com',
                'subject' => 'Test Subject',
                'title'   => 'Your request was approved',
                'message' => 'You may now proceed to the next step.',
                'docControlNumber' => '123123123',
                'button'  => [
                    'url'  => url('/dashboard'),
                    'text' => 'Go to Dashboard',
                ],
            ],
            17
        );

        return response()->json([
            'success' => true,
            'message' => 'Mail sent successfully',
        ]);
    }
}

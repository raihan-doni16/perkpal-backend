<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perk Claim Confirmation</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background: #f7f7f7; padding: 16px; margin: 0;">
  <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
    <!-- Header -->
    <tr>
      <td style="padding: 24px 20px; background: #0c4a34; color: #ffffff; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: 700;">PerkPal</h1>
      </td>
    </tr>

    <!-- Content -->
    <tr>
      <td style="padding: 32px 24px;">
        <h2 style="margin: 0 0 16px 0; color: #111827; font-size: 20px; font-weight: 600;">
          Thank you for your perk claim!
        </h2>

        <p style="margin: 0 0 20px 0; color: #374151; font-size: 15px; line-height: 1.6;">
          Hi <strong>{{ $lead->name }}</strong>,
        </p>

        <p style="margin: 0 0 20px 0; color: #374151; font-size: 15px; line-height: 1.6;">
          We've received your claim for the following perk and our team will review it shortly.
        </p>

        <!-- Perk Details Box -->
        <table width="100%" cellpadding="0" cellspacing="0" style="margin: 24px 0; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; overflow: hidden;">
          <tr>
            <td style="padding: 20px;">
              <h3 style="margin: 0 0 12px 0; color: #0c4a34; font-size: 18px; font-weight: 600;">
                {{ $lead->perk->title ?? 'Perk' }}
              </h3>

              @if($lead->perk)
                <table width="100%" cellpadding="0" cellspacing="0" style="color: #374151; font-size: 14px;">
                  <tr>
                    <td style="padding: 6px 0; width: 35%; font-weight: 600;">Partner:</td>
                    <td style="padding: 6px 0;">{{ $lead->perk->partner_name ?? 'N/A' }}</td>
                  </tr>
                  @if($lead->perk->location)
                  <tr>
                    <td style="padding: 6px 0; width: 35%; font-weight: 600;">Location:</td>
                    <td style="padding: 6px 0; text-transform: uppercase;">{{ $lead->perk->location }}</td>
                  </tr>
                  @endif
                  @if($lead->perk->valid_until)
                  <tr>
                    <td style="padding: 6px 0; width: 35%; font-weight: 600;">Valid Until:</td>
                    <td style="padding: 6px 0;">{{ \Carbon\Carbon::parse($lead->perk->valid_until)->format('M d, Y') }}</td>
                  </tr>
                  @endif
                </table>
              @endif
            </td>
          </tr>
        </table>

        <!-- Your Submission Details -->
        <h3 style="margin: 24px 0 12px 0; color: #111827; font-size: 16px; font-weight: 600;">
          Your Submission Details
        </h3>

        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px; color: #374151; font-size: 14px;">
          <tr>
            <td style="padding: 8px 6px; width: 35%; font-weight: 600; background: #f9fafb; border: 1px solid #e5e7eb;">Name:</td>
            <td style="padding: 8px 6px; border: 1px solid #e5e7eb;">{{ $lead->name }}</td>
          </tr>
          <tr>
            <td style="padding: 8px 6px; width: 35%; font-weight: 600; background: #f9fafb; border: 1px solid #e5e7eb;">Email:</td>
            <td style="padding: 8px 6px; border: 1px solid #e5e7eb;">{{ $lead->email }}</td>
          </tr>
          @if($lead->company)
          <tr>
            <td style="padding: 8px 6px; width: 35%; font-weight: 600; background: #f9fafb; border: 1px solid #e5e7eb;">Company:</td>
            <td style="padding: 8px 6px; border: 1px solid #e5e7eb;">{{ $lead->company }}</td>
          </tr>
          @endif
          @if($lead->phone)
          <tr>
            <td style="padding: 8px 6px; width: 35%; font-weight: 600; background: #f9fafb; border: 1px solid #e5e7eb;">Phone:</td>
            <td style="padding: 8px 6px; border: 1px solid #e5e7eb;">{{ $lead->phone }}</td>
          </tr>
          @endif
          @if($lead->message)
          <tr>
            <td style="padding: 8px 6px; width: 35%; font-weight: 600; background: #f9fafb; border: 1px solid #e5e7eb; vertical-align: top;">Message:</td>
            <td style="padding: 8px 6px; border: 1px solid #e5e7eb;">{{ $lead->message }}</td>
          </tr>
          @endif
        </table>

        <!-- What's Next -->
        <div style="margin: 24px 0; padding: 16px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
          <p style="margin: 0 0 8px 0; color: #92400e; font-size: 14px; font-weight: 600;">
            What's next?
          </p>
          <p style="margin: 0; color: #78350f; font-size: 14px; line-height: 1.5;">
            Our team will review your claim and get back to you within 1-2 business days. You'll receive further instructions on how to redeem this perk via email.
          </p>
        </div>

        <p style="margin: 24px 0 0 0; color: #374151; font-size: 15px; line-height: 1.6;">
          If you have any questions or didn't submit this claim, please contact us immediately.
        </p>
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="padding: 20px 24px; background: #f9fafb; border-top: 1px solid #e5e7eb;">
        <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 13px; text-align: center;">
          Best regards,<br>
          <strong>The Venture Next Team</strong>
        </p>
        <p style="margin: 12px 0 0 0; color: #9ca3af; font-size: 12px; text-align: center;">
          This email was sent automatically. Please do not reply to this email.
        </p>
      </td>
    </tr>
  </table>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Meeting Invitation</title>
<style>
  body { margin: 0; padding: 0; font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fb; }
  .wrap { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
  .header { background: linear-gradient(135deg, #1d4ed8, #4338ca); color: white; padding: 40px 40px 32px; text-align: center; }
  .header h1 { margin: 0 0 8px; font-size: 22px; font-weight: 800; }
  .header p  { margin: 0; opacity: .85; font-size: 14px; }
  .body    { padding: 36px 40px; }
  .greeting { font-size: 16px; color: #1e293b; margin-bottom: 20px; }
  .card    { background: #f8faff; border: 1.5px solid #dbeafe; border-radius: 12px; padding: 20px 24px; margin: 24px 0; }
  .card-row { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 14px; }
  .card-row:last-child { margin-bottom: 0; }
  .icon    { width: 32px; height: 32px; background: #dbeafe; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
  .label   { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; }
  .value   { font-size: 15px; font-weight: 600; color: #1e293b; margin-top: 2px; }
  .cta     { text-align: center; margin: 32px 0 16px; }
  .btn     { display: inline-block; background: linear-gradient(135deg, #1d4ed8, #4338ca); color: #fff !important; text-decoration: none; font-weight: 700; font-size: 15px; padding: 14px 36px; border-radius: 12px; }
  .note    { font-size: 12px; color: #94a3b8; text-align: center; margin-top: 12px; }
  .footer  { background: #f8faff; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center; font-size: 12px; color: #94a3b8; }
</style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <div style="font-size:40px; margin-bottom:12px;">📅</div>
    <h1>You're Invited to a Meeting</h1>
    <p>{{ config('app.name') }} Faculty Portal</p>
  </div>

  <div class="body">
    <p class="greeting">Hi <strong>{{ $recipientName }}</strong>,</p>
    <p style="color:#475569; font-size:14px; line-height:1.7;">
      <strong>{{ $meeting->faculty->name ?? 'Your instructor' }}</strong> has scheduled a live Google Meet session for you. Please join on time using the link below.
    </p>

    <div class="card">
      <div class="card-row">
        <div class="icon">📌</div>
        <div>
          <div class="label">Meeting Topic</div>
          <div class="value">{{ $meeting->title }}</div>
          @if($meeting->description)
          <div style="font-size:13px; color:#64748b; margin-top:4px;">{{ $meeting->description }}</div>
          @endif
        </div>
      </div>

      <div class="card-row">
        <div class="icon">🗓️</div>
        <div>
          <div class="label">Date & Time</div>
          <div class="value">{{ $meeting->start_at->format('l, d F Y') }}</div>
          <div style="font-size:13px; color:#64748b; margin-top:2px;">
            {{ $meeting->start_at->format('h:i A') }} – {{ $meeting->end_at->format('h:i A') }}
            ({{ $meeting->start_at->timezone->getName() }})
          </div>
        </div>
      </div>

      @if($meeting->batch)
      <div class="card-row">
        <div class="icon">👥</div>
        <div>
          <div class="label">Batch</div>
          <div class="value">{{ $meeting->batch->name }}</div>
        </div>
      </div>
      @endif

      <div class="card-row">
        <div class="icon">🔒</div>
        <div>
          <div class="label">Access</div>
          <div class="value">Invite Only</div>
          <div style="font-size:13px; color:#64748b; margin-top:2px;">Only invited participants can join this session.</div>
        </div>
      </div>
    </div>

    <div class="cta">
      <a href="{{ $meeting->meet_link }}" class="btn">
        🎥 &nbsp; Join Google Meet
      </a>
    </div>
    <p class="note">Or copy this link: <a href="{{ $meeting->meet_link }}" style="color:#1d4ed8;">{{ $meeting->meet_link }}</a></p>
  </div>

  <div class="footer">
    <p>© {{ date('Y') }} {{ config('app.name') }}.  This meeting was scheduled via the Faculty Portal.</p>
    <p style="margin-top:4px;">If you weren't expecting this invitation, please ignore this email.</p>
  </div>

</div>
</body>
</html>

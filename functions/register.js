export async function onRequestPost({ request }) {
  const data = await request.json()
  const { username, email } = data

  if (!username || !email) {
    return new Response('Hiányzó adatok', { status: 400 })
  }

  const activationCode = crypto.randomUUID()
  const activateLink = `https://example.com/activate?email=${encodeURIComponent(email)}&code=${activationCode}`

  // 🔑 Secret elérése a környezeti változókból
  const SENDGRID_API_KEY = process.env.MY_SECRET_SENDGRID_API_KEY

  const sendgridResponse = await fetch('https://api.sendgrid.com/v3/mail/send', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${SENDGRID_API_KEY}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      personalizations: [
        { to: [{ email }], subject: 'Aktiválókulcs' }
      ],
      from: { email: 'noreply@example.com', name: 'FilmAjánló' },
      content: [
        { type: 'text/html', value: `<p>Kattints a linkre: <a href="${activateLink}">${activateLink}</a></p>` }
      ]
    })
  })

  if (sendgridResponse.ok) {
    return new Response('E-mail elküldve')
  }

  const err = await sendgridResponse.text()
  return new Response('Hiba: ' + err, { status: 500 })
}

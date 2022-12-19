Parsing:()[
import user from module module.url.post.username
import passwd from module module.url.post.password
if passwd empty so
  method redirect('/')
catch
get hashpasswd from method hash.combine('sha512', passwd)
// procediamo ad autenticare recuperando i file
replace [https://example.com/api/v2/auth/challenge?username={user}&password={hashpasswd}] then url
get response from method HTTP.get(url)
print var response
get response from method json.import(response)
take status response.status
if status not '200' so
  print string 'ERRORE: Il server ha restituito una risposta negativa. Errore in allegato allo status: '
  take response.message message
  print var message
  quit
catch

// Lo status è 200, procedo
method session.manager.inizialize()
method session.manager.set('user', user)
if user is 'Giuly' so
  method redirect('/haha.gif')
  quit
catch
method redirect('/dashboard/mep')
quit
]//,

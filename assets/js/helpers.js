const trackVideoProgress = async (user, video, progress) => {
  try {
    const formData = new FormData()
    formData.set('user', user)
    formData.set('video', video)
    progress && formData.set('progress', progress)

    const config = {
      url: 'http://127.0.0.1:80/cinema/ajax/videoprogress.php',
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      method: 'POST',
      data: formData
    }

    await axios(config)
  } catch (err) {
    console.log('Error: ', err)
  }
}

const markVideoFinished = async (user, video) => {
  try {
    const formData = new FormData()
    formData.set('user', user)
    formData.set('video', video)

    const config = {
      url: 'http://127.0.0.1:80/cinema/ajax/videofinish.php',
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      method: 'POST',
      data: formData
    }

    await axios(config)
  } catch (err) {
    console.log('Error: ', err)
  }
}

const getVideoStart = async (user, video, player, handler) => {
  try {
    const formData = new FormData()
    formData.set('user', user)
    formData.set('video', video)

    const config = {
      url: 'http://127.0.0.1:80/cinema/ajax/videostart.php',
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      method: 'POST',
      data: formData
    }

    const response = await axios(config)
    if (typeof response.data === 'number') {
      player.currentTime = response.data
    }
    player.removeEventListener('canplay', handler)
  } catch (err) {
    console.log('Error: ', err)
  }
}

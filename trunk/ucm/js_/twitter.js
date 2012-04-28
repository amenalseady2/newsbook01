new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 4,
  interval: 6000,
  width: 280,
  height: 300,
  theme: {
    shell: {
      background: '#f4f4f4',
      color: '#5b5b5b'
    },
    tweets: {
      background: '#f4f4f4',
      color: '#515151',
      links: '#00acf0'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: true,
    hashtags: false,
    timestamp: true,
    avatars: false,
    behavior: 'all'
  }
}).render().setUser('SICOTTERecrute').start();

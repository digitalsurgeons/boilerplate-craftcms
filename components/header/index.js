import $ from 'jquery'
import Headroom from 'headroom.js'

class Header {
  constructor(element, options) {
    this.element = $(element)
    this.hamburger = $(options.hamburger)
    this.siteContainer = $(options.siteContainer)
    this.searchButton = $(options.searchButton)
    this.practiceAreasLink = $(options.practiceAreasLink)
    this.servicesLink = $(options.servicesLink)
    this.servicesDropdown = $(options.servicesDropdown)
    this.practiceAreasDropdown = $(options.practiceAreasDropdown)
    this.search = $(options.search)
    this.menu = $(options.menu)
    this.body = $(options.body)
    this.html = $(options.html)
    this.main = $(options.main)
  }

  init() {
    if (!this.element.length) {
      return
    }
    this.initHeadroom()
    this.onClickHamburger()
    this.onClickBody()
    this.onClickSearch()
    this.onClickServices()
    this.onClickPracticeAreas()
    this.onBrowserNext()
  }

  initHeadroom() {
    const headroom = new Headroom(this.element[0], {
      classes: {
        // when element is initialised
        initial: 'header',
        // when scrolling up
        pinned: 'header--pinned',
        // when scrolling down
        unpinned: 'header--unpinned',
        // when above offset
        top: 'header--top',
        // when below offset
        notTop: 'header--notTop',
        // when at bottom of scoll area
        bottom: 'header--bottom',
        // when not at bottom of scroll area
        notBottom: 'header--notBottom'
      },
      tolerance: {
        down: 10,
        up: 20
      },
      // hide active panels
      onUnpin: () => {
        this.servicesLink.removeClass('isActive')
        this.servicesDropdown.removeClass('isActive')
        this.practiceAreasLink.removeClass('isActive')
        this.practiceAreasDropdown.removeClass('isActive')
      }
    })

    headroom.init()
  }

  onClickHamburger() {
    this.hamburger.on('click', () => {
      this.menu.addClass('isActive')
      this.element.addClass('isHidden')
      this.siteContainer.addClass('isMenuRevealed')
      this.body.css('overflow', 'hidden')
      this.main.css('overflow', 'hidden')
      this.html.css('overflow', 'hidden')
    })
  }

  onClickSearch() {
    let statePushed = false
    history.replaceState({ search: 'closed' }, 'Search')
    this.searchButton.on('click', () => {
      this.search.addClass('isActive')
      this.body.css('overflow', 'hidden')
      if (statePushed) history.go(+1)
      else history.pushState({ search: 'opened' }, 'Search')
      statePushed = true
    })
  }

  onBrowserNext() {
    window.addEventListener('popstate', e => {
      if (history.state && history.state.search == 'opened') {
        this.search.addClass('isActive')
        this.body.css('overflow', 'hidden')
      }
    })
  }

  onClickServices() {
    this.servicesLink.on('click', () => {
      if (
        this.element.hasClass('isActive') &&
        this.servicesLink.hasClass('isActive')
      ) {
        this.element.removeClass('isActive')
        this.servicesLink.removeClass('isActive')
        this.servicesDropdown.removeClass('isActive')
      } else {
        this.practiceAreasLink.removeClass('isActive')
        this.practiceAreasDropdown.removeClass('isActive')
        this.element.addClass('isActive')
        this.servicesLink.addClass('isActive')
        this.servicesDropdown.addClass('isActive')
      }
    })
  }

  onClickPracticeAreas() {
    this.practiceAreasLink.on('click', () => {
      if (
        this.element.hasClass('isActive') &&
        this.practiceAreasLink.hasClass('isActive')
      ) {
        this.element.removeClass('isActive')
        this.practiceAreasLink.removeClass('isActive')
        this.practiceAreasDropdown.removeClass('isActive')
      } else {
        this.servicesLink.removeClass('isActive')
        this.servicesDropdown.removeClass('isActive')
        this.element.addClass('isActive')
        this.practiceAreasLink.addClass('isActive')
        this.practiceAreasDropdown.addClass('isActive')
      }
    })
  }

  onClickBody() {
    $('body').click(e => {
      if ($(e.target).closest(this.servicesLink).length) {
        return
      }
      if ($(e.target).closest(this.practiceAreasLink).length) {
        return
      }
      if ($(e.target).closest(this.servicesDropdown).length) {
        return
      }
      if ($(e.target).closest(this.practiceAreasDropdown).length) {
        return
      }

      this.element.removeClass('isActive')
      this.practiceAreasDropdown.removeClass('isActive')
      this.servicesDropdown.removeClass('isActive')
      this.servicesLink.removeClass('isActive')
      this.practiceAreasLink.removeClass('isActive')
    })
  }
}

const header = new Header('.header', {
  hamburger: '.header__hamburger',
  siteContainer: '.main',
  searchButton: '.header__searchButton',
  search: '.search',
  main: '.main',
  practiceAreasLink: '.js-practiceAreas',
  servicesLink: '.js-services',
  servicesDropdown: '.servicesDropdown',
  practiceAreasDropdown: '.practiceAreasDropdown',
  body: 'body',
  menu: '.menu',
  html: 'html'
})

header.init()

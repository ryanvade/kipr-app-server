import { Component, ViewChild } from '@angular/core';
import { Nav, Platform } from 'ionic-angular';
import { StatusBar } from '@ionic-native/status-bar';
import { SplashScreen } from '@ionic-native/splash-screen';

import { SettingsProvider } from '../providers/settings/settings';

import { HomePage } from '../pages/home/home';
import { NewJudgingPage } from '../pages/new-judging/new-judging';
import { MatchesPage } from '../pages/matches/matches';
import { SettingsPage } from '../pages/settings/settings';
import { SignInPage } from '../pages/signInGUI/signIn';
import { Events } from 'ionic-angular';

@Component({
  templateUrl: 'app.html'
})
export class MyApp {
  @ViewChild(Nav) nav: Nav;

  rootPage: any = HomePage;

  pages: Array<{ title: string, component: any }>;

  constructor(public platform: Platform, public statusBar: StatusBar, public splashScreen: SplashScreen, public settings: SettingsProvider, public events: Events) {
    this.initializeApp();

    // used for an example of ngFor and navigation
    this.pages = [
      { title: 'Home', component: HomePage },
      { title: 'Matches', component: MatchesPage },
      { title: 'Settings', component: SettingsPage }
    ];

    this.maybeAddAuthenticatedPages();

    events.subscribe('authentication:judging', (enabled) => {
      if(enabled) {
        this.pages.push({ title: 'Judging', component: NewJudgingPage });
      }else {
        let index = this.pages.indexOf({ title: 'Judging', component: NewJudgingPage });
        if(index) {
          this.pages.splice(index, 1);
        }
      }
    });

    events.subscribe('authentication:signin', (enabled) => {
      if(enabled) {
        this.pages.push({ title: 'Team Sign In', component: SignInPage });
      }else {
        let index = this.pages.indexOf({ title: 'Team Sign In', component: SignInPage });
        if(index) {
          this.pages.splice(index, 1);
        }
      }
    })
  }

  async maybeAddAuthenticatedPages() {
    let signInToken = await this.settings.getSignInAuthToken();
    let judgingToken = await this.settings.getAuthToken();

    if(signInToken != null && signInToken != "") {
      this.pages.push({ title: 'Team Sign In', component: SignInPage });
    }

    if(judgingToken != null && judgingToken != "") {
      this.pages.push({ title: 'Judging', component: NewJudgingPage });
    }

  }

  initializeApp() {
    this.platform.ready().then(() => {
      // App Startup

      this.settings.getFirstTimeUse().then(val => {
        if (val != 'TRUE') {
          this.firstTimeUse();
        }
        this.statusBar.styleDefault();
        this.splashScreen.hide();
      });

    });
  }

  openPage(page) {
    // Reset the content nav to have just this page
    // we wouldn't want the back button to show in this scenario
    this.nav.setRoot(page.component);
  }

  checkForJudging(page?) {
    if(page != null){
      if(page.title != 'NewJudgingPage') {
        return true;
      }
    }

    if(this.judgingAuthenticated()) {
      return true;
    }

    return false;
  }

 judgingAuthenticated() {
   // some logic to check for authentication...
   //let token = this.settings.getAuthToken().then(val => {});
   //if(true){//check text? add judging page to menu
     this.pages.push({title: 'Judging', component: NewJudgingPage});
     console.log('Judge Authenticated');
     return true;
   //}
   //return false;
  }

  firstTimeUse() {
    console.log('First Time Use');
    this.settings.setDefaults();
  }
}

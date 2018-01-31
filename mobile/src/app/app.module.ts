import { BrowserModule } from '@angular/platform-browser';
import { ErrorHandler, NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { IonicApp, IonicErrorHandler, IonicModule } from 'ionic-angular';
import { IonicStorageModule } from '@ionic/storage';
import { Network } from '@ionic-native/network';

import { MyApp } from './app.component';
import { HomePage } from '../pages/home/home';
import { JudgingPage } from '../pages/judging/judging';
import { MatchesPage } from '../pages/matches/matches';
import { SettingsPage } from '../pages/settings/settings';
import { SignInPage } from '../pages/signInGUI/signIn';

import { StatusBar } from '@ionic-native/status-bar';
import { SplashScreen } from '@ionic-native/splash-screen';
import { BarcodeScanner } from '@ionic-native/barcode-scanner';
import { OpenNativeSettings } from '@ionic-native/open-native-settings';
import { RemoteServiceProvider } from '../providers/remote-service/remote-service';
import { SettingsProvider } from '../providers/settings/settings';
import { CompetitionProvider } from '../providers/competition/competition';
import { StatusProvider } from '../providers/status/status';

@NgModule({
  declarations: [
    MyApp,
    HomePage,
    JudgingPage,
    MatchesPage,
    SettingsPage,
    SignInPage
  ],
  imports: [
    BrowserModule,
    IonicModule.forRoot(MyApp),
    IonicStorageModule.forRoot(),
    HttpModule
  ],
  bootstrap: [IonicApp],
  entryComponents: [
    MyApp,
    HomePage,
    JudgingPage,
    MatchesPage,
    SettingsPage,
    SignInPage
  ],
  providers: [
    StatusBar,
    Network,
    SplashScreen,
    OpenNativeSettings,
    {provide: ErrorHandler, useClass: IonicErrorHandler},
    BarcodeScanner,
    RemoteServiceProvider,
    SettingsProvider,
    CompetitionProvider,
    StatusProvider
  ]
})
export class AppModule {}

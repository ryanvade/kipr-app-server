import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { ViewChild } from '@angular/core';
import { RemoteServiceProvider } from '../../providers/remote-service/remote-service';

@IonicPage()
@Component({
  selector: 'page-signIn',
  templateUrl: 'signIn.html'
})

export class SignInPage {

ionViewDidLoad() {
    console.log('ionViewDidLoad SignInPage');
  }
}
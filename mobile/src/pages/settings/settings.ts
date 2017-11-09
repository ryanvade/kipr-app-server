import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { QRScanner, QRScannerStatus } from '@ionic-native/qr-scanner';
/**
 * Generated class for the JudgingPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-settings',
  templateUrl: 'settings.html',
})
export class SettingsPage {

  constructor(public navCtrl: NavController, public navParams: NavParams, private qrScanner: QRScanner) { 
             
  }

funct(){
  
  console.log('asdl;fjksadf');
  this.qrScanner.prepare()
  .then((status: QRScannerStatus) => {
     if (status.authorized) {
       // start scanning
       let scanSub = this.qrScanner.scan().subscribe((text: string) => {
         console.log('Scanning', text);

         this.qrScanner.hide(); 
         scanSub.unsubscribe(); 
       });

       // show camera preview
       this.qrScanner.show();

     } else if (status.denied) {
     } else {
     }
  })
  .catch((e: any) => console.log('Error is', e));
}
  
  ionViewDidLoad() {
    console.log('Loaded');
  }
}
import { Injectable } from '@angular/core';
import { Http, Headers, RequestOptions } from '@angular/http';
import 'rxjs/add/operator/map';

/*
  Generated class for the StatusProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class StatusProvider {

  constructor(public http: Http) {
    console.log('Hello StatusProvider Provider');
  }

   async validateJudgingAuthToken(token, serverUrl) {
    let passport = await this.getValidToken(token, serverUrl).catch((error) => {
      console.error("Unable to get Auth Token");
      return false;
    });
    console.log(passport);
    let now = new Date();
    if(passport.id == token && passport.scopes.contains('judging') && (new Date(passport.expires_at)) > now) {
      return true;
    }
    return false;
  }

  async validateSignInAuthToken(token, serverUrl) {
   let passport = await this.getValidToken(token, serverUrl).catch((error) => {
     console.error("Unable to get Auth Token");
     return false;
   });
   console.log(passport);
   let now = new Date();
   if(passport.id == token && passport.scopes.contains('sign_in') && (new Date(passport.expires_at)) > now) {
     return true;
   }
   return false;
 }

  async getValidToken(token, serverUrl) {
    let url = serverUrl +  "/api/auth/token";
    let headers = new Headers();
    headers.append('Authorization', 'Bearer ' + token);
    return this.http.get(url, {headers: headers})
        .map(res => res.json())
        .toPromise();
  }

}

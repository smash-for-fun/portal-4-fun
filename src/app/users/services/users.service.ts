import { Injectable } from '@angular/core';
import { User } from '../models';
import { StoreHelper } from './store-helper';
import { ApiService } from '../../api.service';
import 'rxjs/Rx';

@Injectable()
export class UserService {

  path: string = '/users';
  constructor(private storeHelper: StoreHelper, private apiService: ApiService) {}

  createUser(user: User) {
    return this.apiService.post(this.path, user)
    .do(savedUser => this.storeHelper.add('users', savedUser))
  }

  getUsers() {
    return this.apiService.get(this.path)
    .do(res => this.storeHelper.update('users', res.data));
  }

  completeUser(user: User) {
    return this.apiService.delete(`${this.path}/${user.id}`)
    .do(res => this.storeHelper.findAndDelete('users', res.id));
  }
}


import http from 'k6/http';
import { sleep } from 'k6';

export let options = {
  vus: 10000,           // 10,000 virtual users
  duration: '10m',      // run the test for 10 minutes
};

export default function () {
  http.get('http://carshare-env.eba-xhpyenx6.ap-southeast-2.elasticbeanstalk.com/');
  sleep(1); // simulate user “think time”
}

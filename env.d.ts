import PusherStatic from 'pusher-js';
import EchoStatic from 'laravel-echo';

declare global {
  // globals
  const Pusher: typeof PusherStatic;
  const Echo: EchoStatic<any>;

  // extend to window
  interface Window {
    Pusher: typeof PusherStatic;
    Echo: EchoStatic<any>;
  }
}

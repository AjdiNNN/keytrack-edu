from pynput import keyboard
from pynput import mouse
from datetime import datetime
import psutil
import platform
import pygetwindow as gw
import requests
import asyncio
import atexit

session = {"sessionid": 0, "start": None, "end": None}

def update():
    requests.put(URL+"/session/"+ session['sessionid'], json={"end": str(datetime.now())}, headers={"Authorization": passcode})
atexit.register(update)

URL = 'http://localhost/keytrack-edu/api'
passcode = 'PyhtonKey-edu!'

def is_vscode_running():
    if platform.system() == "Windows":
        for proc in psutil.process_iter(['pid', 'name']):
            if "Code.exe" == proc.info['name']:
                return True
    elif platform.system() == "Linux":
        for proc in psutil.process_iter(['pid', 'name']):
            if "code" == proc.info['name']:
                return True
    return False

def on_press(key):
    if is_vscode_running():
        try:
            active_window = gw.getActiveWindow()
            if active_window is not None and "Visual Studio Code" in active_window.title:
                try:
                    code = int(''.join(f'{ord(c)}' for c in key.char))
                    data = {"pressed": key.char if code > 32 else code, "pressedAt": str(datetime.now()), "special": False, "session_id": session['sessionid']}
                    requests.post(URL+"/keyboard", json=data, headers={"Authorization": passcode})
                except AttributeError:
                    data = {"pressed":  str(key), "pressedAt": str(datetime.now()), "special": True, "session_id": session['sessionid']}
                    requests.post(URL+"/keyboard", json=data, headers={"Authorization": passcode})
        except gw.PyGetWindowException:
            pass
    else:
        requests.put(URL+"/session/"+ session['sessionid'], json={"end": str(datetime.now())}, headers={"Authorization": passcode})
        asyncio.run(main())

def on_click(x, y, button, pressed):
    if is_vscode_running():
        try:
            active_window = gw.getActiveWindow()
            if active_window is not None and "Visual Studio Code" in active_window.title and active_window.isMaximized:
                    data = {"x": x,"y": y, "pressedAt": str(datetime.now()), "isRight": button is button.right, "released": pressed is False,"session_id": session['sessionid']}
                    requests.post(URL+"/mouse", json=data, headers={"Authorization": passcode})
        except gw.PyGetWindowException:
            pass
    else:
        requests.put(URL+"/session/"+ session['sessionid'], json={"end": str(datetime.now())}, headers={"Authorization": passcode})
        asyncio.run(main())

async def main():
    while is_vscode_running is False:
        await asyncio.sleep(1)
    r = requests.post(URL+"/session", json={"start": str(datetime.now()), "end": str(datetime.now())}, headers={"Authorization": passcode})
    global session
    session = {"sessionid": r.json()['id'], "start": r.json()['id'], "end": None}

    keyboard_listener = keyboard.Listener(
    on_press=on_press)
    mouse_listener = mouse.Listener(
    on_click=on_click)
    keyboard_listener.start()
    mouse_listener.start()
    keyboard_listener.join()
    mouse_listener.join()


asyncio.run(main())


from pynput import keyboard
from pynput.keyboard import Key
from pynput import mouse
from datetime import datetime
import pygetwindow as gw
import requests
import asyncio
import subprocess
import subprocess
import sys

stopped = True
session = {"sessionid": 0, "start": None, "end": None}

def start_new_session():
    r = requests.post(URL+"/session", json={"start": str(datetime.now()), "end": str(datetime.now())}, headers={"Authorization": passcode})
    global session
    session = {"sessionid": r.json()['id'], "start": r.json()['id'], "end": None}

URL = 'http://localhost/keytrack-edu/api'
passcode = 'PyhtonKey-edu!'

def is_process_running(process_name):
    command = ""
    if sys.platform.startswith("win"):
        command = f'tasklist /FI "IMAGENAME eq {process_name}.exe"'
    elif sys.platform.startswith("linux"):
        command = f'pgrep {process_name}'
    else:
        raise NotImplementedError("Unsupported platform")

    try:
        call = subprocess.check_output(command, shell=True)
        return not call==b'INFO: No tasks are running which match the specified criteria.\r\n'
    except subprocess.CalledProcessError:
        return False

def is_vsc_running():
    vsc_process_names = ["code", "code.exe"]
    for process_name in vsc_process_names:
        if is_process_running(process_name):
            return True
    return False

def is_eclipse_running():
    eclipse_process_name = "eclipse"
    return is_process_running(eclipse_process_name)

def on_press(key):
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

def on_click(x, y, button, pressed):
    try:
        active_window = gw.getActiveWindow()
        if active_window is not None and "Visual Studio Code" in active_window.title and active_window.isMaximized:
            data = {"x": x,"y": y, "pressedAt": str(datetime.now()), "isRight": button is button.right, "released": pressed is False,"session_id": session['sessionid']}
            requests.post(URL+"/mouse", json=data, headers={"Authorization": passcode})
    except gw.PyGetWindowException:
        pass

keyboard_listener = keyboard.Listener(on_press=on_press)
mouse_listener = mouse.Listener(on_click=on_click)

async def check_restarted():
    global keyboard_listener
    global mouse_listener
    while not is_vsc_running():
        await asyncio.sleep(30)
    start_new_session()
    if not keyboard_listener.running:
        keyboard_listener.start()
        mouse_listener.start()
        keyboard_listener.join()
        mouse_listener.join()

asyncio.run(check_restarted())

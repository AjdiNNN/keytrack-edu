from pynput import keyboard
from pynput.keyboard import Key
from pynput import mouse
from datetime import datetime
import pygetwindow as gw
import requests
import time
import subprocess
import sys
import customtkinter as ctk
import webbrowser
import os

session = {"sessionid": 0, "start": None, "end": None}
URL = 'https://keytrack-edu-rest.vercel.app/'
jwt = None
error = None
def start_login():
    ctk.set_appearance_mode("dark")
    ctk.set_default_color_theme("blue")
    app = ctk.CTk()
    app.geometry("400x450")
    app.title("KeyTrack-Edu")

    def login():
        global error
        if error != None:
            error.destroy
        r = requests.post(URL + "login", json={"username": user_entry.get(), "password": user_pass.get()})
        if 'message' in r.json():
            error = ctk.CTkLabel(master=frame,text=r.json()['message'], text_color=("red"))
            error.pack(pady=5,padx=5)
            error.after(3000, error.destroy)
        else:
            global jwt
            jwt = r.json()['token']
            f = open("jwt.txt", "a")
            f.write(jwt)
            f.close()
            error = ctk.CTkLabel(master=frame,text='Logged in!', text_color=("green"))
            error.pack(pady=5,padx=5)
            app.after(3000,app.destroy)
        return
    
    
    label = ctk.CTkLabel(app,text="Welcome!")
    
    label.pack(pady=20)
    
    
    frame = ctk.CTkFrame(master=app)
    frame.pack(pady=20,padx=40,fill='both',expand=True)
    
    label = ctk.CTkLabel(master=frame,text='Please sign in')
    label.pack(pady=12,padx=10)
    
    
    user_entry= ctk.CTkEntry(master=frame,placeholder_text="Username")
    user_entry.pack(pady=12,padx=10)
    
    user_pass= ctk.CTkEntry(master=frame,placeholder_text="Password",show="*")
    user_pass.pack(pady=12,padx=10)

    
    button = ctk.CTkButton(master=frame,text='Login',command=login)
    button.pack(pady=12,padx=10)
    

    link1 = ctk.CTkLabel(master=frame, text="No account?")
    link1.pack(pady=12,padx=10)
    link1.bind("<Button-1>", lambda e: webbrowser.open_new("https://keytrack-edu-front.vercel.app/login.html"))
    app.mainloop()

try:
    f = open("jwt.txt", "r")
    jwt = f.read()
    r = requests.get(URL, headers={"Authorization": jwt})
    if 'message' in r.json():
        start_login()
except IOError:
    start_login()

keyboard_listener = None
mouse_listener = None

def main():
    global keyboard_listener
    global mouse_listene
    while not is_vsc_running():
        time.sleep(15)
    start_new_session()
    global keyboard_listener
    global mouse_listener
    keyboard_listener = keyboard.Listener(on_press=on_press)
    mouse_listener = mouse.Listener(on_click=on_click)
    keyboard_listener.start()
    mouse_listener.start()
    keyboard_listener.join()
    mouse_listener.join()
    main()

def start_new_session():
    r = requests.post(URL + "session", json={"start": str(datetime.now()), "end": str(datetime.now())},
                      headers={"Authorization": jwt})
    print(r.json())
    global session
    session = {"sessionid": r.json()['id'], "start": r.json()['start'], "end": None}

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

def on_press(key):
    try:
        active_window = gw.getActiveWindow()
        if active_window is not None and "Visual Studio Code" in active_window.title:
            try:
                code = int(''.join(f'{ord(c)}' for c in key.char))
                data = {"pressed": key.char if code > 32 else code, "pressedAt": str(datetime.now()), "special": False, "session_id": session['sessionid']}
                requests.post(URL+"keyboard", json=data, headers={"Authorization": jwt})
            except AttributeError:
                data = {"pressed":  str(key), "pressedAt": str(datetime.now()), "special": True, "session_id": session['sessionid']}
                requests.post(URL+"keyboard", json=data, headers={"Authorization": jwt})
            except TypeError:
                pass
        elif not is_vsc_running():
            global keyboard_listener
            global mouse_listener
            mouse_listener.stop()
            keyboard_listener.stop()
    except gw.PyGetWindowException:
        pass

def on_click(x, y, button, pressed):
    try:
        active_window = gw.getActiveWindow()
        if active_window is not None and "Visual Studio Code" in active_window.title and active_window.isMaximized:
            data = {"x": x,"y": y, "pressedAt": str(datetime.now()), "isRight": button is button.right, "released": pressed is False,"session_id": session['sessionid']}
            requests.post(URL+"mouse", json=data, headers={"Authorization": jwt})
        elif not is_vsc_running():
            global keyboard_listener
            global mouse_listener
            keyboard_listener.stop()
            mouse_listener.stop()
    except gw.PyGetWindowException:
        pass
main()
from http.server import HTTPServer, BaseHTTPRequestHandler
from io import BytesIO
import json
import downlink
import LSTM_Model
import numpy as np

cur_ip = "localhost"
cur_port = 8080


def formatData(array, error):
    formatted = {}
    i = 0
    for x in array:
        label = str(i)
        formatted[label] = round(x[0],2)
        i += 1
    label = str(i)
    formatted[label] = round(error, 2)
    i += 1
    formatted["size"] = i
    return formatted

class SimpleHTTPRequestHandler(BaseHTTPRequestHandler):

    def _set_headers(self):
        self.send_response(200)
        self.send_header('Content-type', 'application/json')
        self.end_headers()

    def do_HEAD(self):
        self._set_headers()

    def do_GET(self):
        if self.path[0] == '/':
            requested = self.path[1:] + '"'
            values = {}
            allFound = False
            singleFound = False
            my_i = 0
            while not allFound:
                name = ""
                value = ""
                while not singleFound:
                    if requested[my_i] == '=':
                        singleFound = True
                    else:
                        name += requested[my_i]
                    my_i += 1
                singleFound = False
                while not singleFound:
                    if requested[my_i] == '&':
                        singleFound = True
                    elif requested[my_i] == '"':
                        singleFound = True
                        allFound = True
                    else:
                        value += requested[my_i]
                    my_i += 1
                singleFound = False
                values[name] = value

            downlink.downloadPredictions(values["nodeID"], values["date"])
            # output = formatData(LSTM_Model.predict().tolist(), values["date"])
            predictions, error = LSTM_Model.predict("LSTM_model_" + values["nodeID"] + ".h5");
            output = formatData(predictions, error)

            print("")
            print("RECEIVED: ", values)
            print("SENDING: ", output)
        self._set_headers()
        self.wfile.write(str.encode(json.dumps(output)))

    def do_POST(self):
        content_length = int(self.headers['Content-Length'])
        body = self.rfile.read(content_length)
        self.send_response(200)
        self.end_headers()
        response = BytesIO()
        response.write(b'This is POST request. ')
        response.write(b'Received: ')
        response.write(body)
        self.wfile.write(response.getvalue())


httpd = HTTPServer((cur_ip, cur_port), SimpleHTTPRequestHandler)
print("Running python HTTPServer at ", cur_ip, ":", cur_port, "...")
print("Waiting for incoming connections ...")
httpd.serve_forever()

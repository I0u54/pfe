<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User ;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class MessageCotroller extends Controller
{
    use HttpResponses ;
    public function createConversation(Request $request , $idReceiver)
    {
        $messageText = $request->input('message_text');
        $receiver = User::find($idReceiver);
        if($receiver) :
            Message::create([
                'idSender' => Auth::user()->id,
                'idReceiver' => $idReceiver,
                'message_text' => $messageText,
            ]);

            return $this->success([] , 'message created successfully');

        endif;    

        return $this->error([] , 'user not found' , 403);
    }
    

    public function getConversations($userId)
    {
        $user = User::find($userId);
    
        $messages = collect();
    
        $user->sentMessages->groupBy('idReceiver')->each(function ($sentMessages, $idReceiver) use ($user, &$messages) {
            $receiver = User::find($idReceiver);
            $receivedMessages = $user->receivedMessages->where('idSender', $idReceiver);
            
            $conversationMessages = $sentMessages->concat($receivedMessages)->sortBy('created_at')->values();
    
            $conversation = [
                'idReceiver' => $idReceiver,
                'receiver_name' => $receiver->name,
                'receiver_email' => $receiver->email,
                'messages' => $conversationMessages->map(function ($message) use ($user, $receiver) {
                    $senderName = ($message->idSender == $user->id) ? $user->name : $receiver->name;
                    return [
                        'idMessage' => $message->idMessage,
                        'idSender' => $message->idSender,
                        'sender_name' => $senderName,
                        'idReceiver' => $message->idReceiver,
                        'message_text' => $message->message_text,
                        'created_at' => $message->created_at,
                        'updated_at' => $message->updated_at,
                        
                        
                    ];
                }),
            ];
    
            $messages->push($conversation);
        });
    
        return response()->json($messages);
    }
    
    

    // public function deleteMessage ($id)
    // {
    //     $message = Mesasge::find($id);

    //     if (!$message) {
    //         return response()->json(['message' => 'Message not found'], 404);
    //     }

    //     $message->delete();

    //     return response()->json(['message' => 'Message deleted successfully']);
    // }


    public function deleteConversation(Request $request, $userId)
    {
        Message::where('idSender', $userId)->orWhere('idReceiver', $userId)->delete();

        return response()->json(['message' => 'Conversation deleted successfully']);
    }
}


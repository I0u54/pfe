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
    
        // Fetch the received messages for the user
        $receivedMessages = $user->receivedMessages;
    
        // Get the distinct IDs of the senders from received messages
        $senderIds = $receivedMessages->pluck('idSender')->unique();
    
        // Loop through the sender IDs and retrieve the corresponding sender users
        foreach ($senderIds as $senderId) {
            // Retrieve the sender user
            $sender = User::find($senderId);
    
            // Filter the received messages for the current sender
            $senderReceivedMessages = $receivedMessages->where('idSender', $senderId);
    
            // Get the sent messages by the user to the current sender
            $sentMessages = $user->sentMessages->where('idReceiver', $senderId);
    
            // Combine the sent and received messages
            $conversationMessages = $sentMessages->concat($senderReceivedMessages)
                ->sortBy('created_at')->values();
    
            // Create the conversation array
            $conversation = [
                'idReceiver' => $senderId,
                'receiver_name' => $sender->name,
                'receiver_email' => $sender->email,
                'messages' => $conversationMessages->map(function ($message) use ($user, $sender) {
                    $senderName = ($message->idSender == $user->id) ? $user->name : $sender->name;
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
        }
    
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


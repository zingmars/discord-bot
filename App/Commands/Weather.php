<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use App\Helpers\Env;
use Exception;

class Weather extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->arguments)) {
            $reply = 'syntax: %s%s [message]';
            $this->reply(sprintf($reply, Env::Get('COMMAND_PREFIX'), $this->name));
            $this->react('❌');
            return false;
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $apiKey = Env::Get('OPENWEATHER_API_KEY');
        if (empty($apiKey)) {
            $this->reply('API key not set. No weather for you :(');
            $this->react('❌');
            return;
        }

        $url = 'http://api.openweathermap.org/data/2.5/weather?q=%s&units=metric&appid=%s';
        $url = sprintf($url, urlencode($this->arguments[0]), $apiKey);

        $output = Curl::Get($url);
        $result = json_decode($output);

        if (isset($result->message)) {
            $message = "Encountered an error: %s.";
            $message = sprintf($message, $result->message);
            $this->react('❌');
            $this->reply($message);
            return;
        }

        $temp = $result->main->temp;
        $description = $result->weather[0]->main;

        // Implementation shamelessly stolen off Taiiwobot who then stole it from god knows where.
        if ($temp <= 6.0) {
            $remark = "IT'S FUCKING COLD";
            $flavours = [
                "Where's the cat? Oh shit. Fluffy's frozen.",
                "Nothing a few shots couldn't fix",
                "Should have gone south",
                "You think this is cold? Have you been to upstate New York?",
                "Why do I live here?", "wang icicles.",
                "Freezing my balls off out here", "Fuck this place.",
                "GREAT! If you're a penguin.", "Fresh off the tap.",
                "Fantastic do-nothing weather.",
                "Put on some fucking socks.", "Blue balls x 2",
                "Good news, food won't spoil nearly as fast outside. Bad news, who cares?",
                "Really?", "Wear a fucking jacket.",
                "I hear Siberia is the same this time of year.",
                "NOT FUCKING JOGGING WEATHER", "Shrinkage's best friend.",
                "Warmer than Hoth.", "Good baby making weather.",
                "Where's a Tauntaun when you need one?",
                "My nipples could cut glass", "Global Warming? Bullshit.",
                "Call your local travel agency and ask them if they're serious.",
                "Freezing my balls off IN here",
                "I'm not sure how you can stand it", "I'm sorry.",
                "Even penguins are wearing jackets.",
                "Keep track of your local old people.",
                "WHAT THE FUCK DO YOU MEAN IT'S NICER IN ALASKA?",
                "Sock warmers are go. Everywhere.",
                "Why does my car feel like a pair of ice skates?",
                "Actually, a sharp-stick in the eye might not all be that bad right now.",
                "THO Season.", "It's a tit-bit nipplie.",
                "Anything wooden will make a good fireplace. Thank us later.",
                "MOVE THE FUCK ON GOLDILOCKS",
                "I'm defrosting inside of my freezer.",
                "It's time for a vacation.",
                "It's bone chilling cold out. Sorry ladies."];
        } else if ($temp <= 20.0) {
            $remark = "IT'S FUCKING...ALRIGHT";
            $flavours = [
                "Might as well rain, I'm not going out in that.",
                "Better than a sharp stick in the eye.",
                "Everything's nice butter weather!",
                "At least you aren't living in a small town in Alaska",
                "It could be worse.", "FUCKING NOTHING TO SEE HERE",
                "Listen, weather. We need to have a talk.",
                "OH NO. THE WEATHER MACHINE IS BROKEN.",
                "An Eskimo would beat your ass to be here",
                "Where life is mediocre",
                "Can't complain about today, but I want to!",
                "Maybe inviting the inlaws over will improve today.",
                "Let's go to the beach! In three months when it's nice again...",
                "From inside it looks nice out.", "WHAT THE FUCK EVER",
                "I love keeping the heat on for this long.",
                "Inside or outside? Either way it's still today.",
                "It's either only going to get better or worse from here!",
                "If it's raining cats and dogs, hope you're not a pet person.",
                "Today makes warm showers way nicer.",
                "Here's to making your blankets feel useful.",
                "I've seen better days",
                "Compared to how awful it's been this is great!",
                "If we go running maybe we won't notice.",
                "Is that the sun outside? Why isn't it doing anything?",
                "Well, at least we're not in prison.",
                "Slap me around and call me Sally. It'd be an improvement.",
                "Today is the perfect size, really honey.",
                "Maybe Jersey Shore is on tonight."];
        } else if ($temp >= 20.3 && $temp <= 20.6) {
            // 20.5 = 69F. We approximate.
            $remark = "IT'S FUCKING SEXY TIME (~69F)";
            $flavours = [
                "Why is 77 better than 69? You get eight more.",
                "What comes after 69? Mouthwash.",
                "If you are given two contradictory orders, obey them both.",
                "a good fuckin' time! ;)",
                "What's the square root of 69? Eight something."];
        } else if ($temp <= 27.0) {
            $remark = "IT'S FUCKING NICE";
            $flavours = [
                "I made today breakfast in bed.", "FUCKING SWEET",
                "Quit your bitching", "Enjoy.", "IT'S ABOUT FUCKING TIME",
                "READ A FUCKIN' BOOK", "LETS HAVE A FUCKING PICNIC",
                "It is safe to take your ball-mittens off.", "More please.",
                "uh, can we trade?", "WOO, Spring Break!",
                "I can't believe it's not porn!", "I approve of this message!",
                "Operation beach volleyball is go.", "Plucky ducky kinda day.",
                "Today called just to say \"Hi.\"",
                "STOP AND SMELL THE FUCKING ROSES",
                "FUCKING NOTHING WRONG WITH TODAY", "LETS HAVE A FUCKING SOIREE",
                "What would you do for a holyshititsniceout bar?",
                "There are no rules today, blow shit up!",
                "Celebrate Today's Day and buy your Today a present so it knows you care.",
                "I feel bad about playing on my computer all day.",
                "Party in the woods.", "It is now safe to leave your home.",
                "PUT A FUCKING CAPE ON TODAY, BECAUSE IT'S SUPER",
                "Today is like \"ice\" if it started with an \"n\". Fuck you, we don't mean nce.",
                "Water park! Water drive! Just get wet!",
                "The geese are on their way back! Unless you live where they migrate to for the winter.",
                "FUCKING AFFABLE AS SHIT", "Give the sun a raise!",
                "Today is better than an original holographic charizard. Loser!"];
        } else {
            $remark = "IT'S FUCKING HOT";
            $flavours = [
                "Do you have life insurance?",
                "Like super models, IT'S TOO FUCKING HOT.",
                "Not even PAM can make me not stick to this seat",
                "SWIMMIN HOLE!",
                "Time to crank the AC.",
                "THE FUCKING EQUATER CALLED, AND IT'S JEALOUS.",
                "Looked in the fridge this morning for some eggs. They're already cooked.",
                "Keeping the AC business in business.",
                "I burned my feet walking on grass.",
                "times you wish you didn't have leather seats",
                "Isn't the desert nice this time of year?",
                "Why, oh why did we decide to live in an oven?",
                "It's hotter outside than my fever.",
                "I recommend staying away from fat people.",
                "TAKE IT OFF!",
                "Even your frigid girlfriend can't save you from today.",
                "I need gloves to touch the steering wheel.",
                "Lock up yo' ice cream trucks, lock up yo' wife.",
                "FUCKING SUNBURNED, AND I WAS INSIDE ALL DAY.",
                "Fuck this shit, I'm moving back to Alaska."];
        }

        if ($description === "thunderstorm") {
            $remark .= " AND THUNDERING";
        } else if ($description === "snow" || $description === "snow grains") {
            $remark .= " AND SNOWING";
        } else if ($description === "drizzle" || $description === "rain" || $description === "unknown precipation") {
            $remark .= " AND WET";
        } else if ($description === "ice crystals" || $description === "ice pellets") {
            $remark .= " AND ICY";
        } else if ($description === "hail" || $description === "small hail") {
            $remark .= " AND HAILING";
        }

        $flavour = $flavours[array_rand($flavours)];

        $response = "%s°C - %s - %s - %s, %s at %s";
        $response = sprintf($response, $temp, $remark, $flavour, $result->name, $result->sys->country, date("Y-m-d H:i T"));

        $this->reply($response);
    }
}
